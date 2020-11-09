<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private string $alias;
    private \Graphpinator\Parser\Value\NamedValueSet $arguments;
    private \Graphpinator\Normalizer\Directive\DirectiveSet $directives;
    private ?\Graphpinator\Normalizer\FieldSet $children = null;
    private ?\Graphpinator\Type\Contract\NamedDefinition $typeCond = null;

    public function __construct(
        \Graphpinator\Parser\Field $parserField,
        \Graphpinator\Type\Contract\NamedDefinition $parentType,
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions
    )
    {
        $this->name = $parserField->getName();
        $this->alias = $parserField->getAlias()
            ?? $this->name;
        $this->arguments = $parserField->getArguments()
            ?? new \Graphpinator\Parser\Value\NamedValueSet([]);

        $field = $parentType->getField($this->name);
        $fieldType = $field->getType()->getNamedType();

        foreach ($this->arguments as $argument) {
            if (!$field->getArguments()->offsetExists($argument->getName())) {
                throw new \Graphpinator\Exception\Normalizer\UnknownFieldArgument($argument->getName(), $field->getName(), $parentType->getName());
            }
        }

        $this->directives = $parserField->getDirectives() instanceof \Graphpinator\Parser\Directive\DirectiveSet
            ? $parserField->getDirectives()->normalize($typeContainer)
            : new \Graphpinator\Normalizer\Directive\DirectiveSet([], \Graphpinator\Directive\ExecutableDirectiveLocation::FIELD);

        if ($parserField->getFields() instanceof \Graphpinator\Parser\FieldSet) {
            $this->children = $parserField->getFields()->normalize($fieldType, $typeContainer, $fragmentDefinitions);
        } elseif (!$fieldType instanceof \Graphpinator\Type\Contract\LeafDefinition) {
            throw new \Graphpinator\Exception\Resolver\SelectionOnComposite();
        }
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getAlias() : string
    {
        return $this->alias;
    }

    public function getArguments() : \Graphpinator\Parser\Value\NamedValueSet
    {
        return $this->arguments;
    }

    public function getDirectives() : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function getFields() : ?\Graphpinator\Normalizer\FieldSet
    {
        return $this->children;
    }

    public function getTypeCondition() : ?\Graphpinator\Type\Contract\NamedDefinition
    {
        return $this->typeCond;
    }

    public function applyFragmentTypeCondition(?\Graphpinator\Type\Contract\NamedDefinition $typeCond) : void
    {
        if (!$typeCond instanceof \Graphpinator\Type\Contract\NamedDefinition) {
            return;
        }

        if (!$this->typeCond instanceof \Graphpinator\Type\Contract\NamedDefinition) {
            $this->typeCond = $typeCond;

            return;
        }

        if ($this->typeCond->isInstanceOf($typeCond)) {
            return;
        }

        throw new \Graphpinator\Exception\Normalizer\InvalidFragmentType($this->typeCond->getName(), $typeCond->getName());
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : self
    {
        $clone = clone $this;
        $clone->arguments = $this->arguments->applyVariables($variables);
        $clone->directives = $this->directives->applyVariables($variables);
        $clone->children = $this->children instanceof FieldSet
            ? $this->children->applyVariables($variables)
            : null;

        return $clone;
    }

    public function mergeField(Field $field) : self
    {
        if ($this->getName() !== $field->getName()) {
            throw new \Graphpinator\Exception\Normalizer\ConflictingFieldAlias();
        }

        $fieldArguments = $field->getArguments();

        foreach ($this->getArguments() as $lhs) {
            if (isset($fieldArguments[$lhs->getName()]) &&
                $lhs->getValue()->isSame($fieldArguments[$lhs->getName()]->getValue())) {
                continue;
            }

            throw new \Graphpinator\Exception\Normalizer\ConflictingFieldArguments();
        }

        if ($this->children instanceof FieldSet) {
            $this->children->mergeFieldSet($field->getFields());
        }

        return $this;
    }
}
