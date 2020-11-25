<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Field;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private string $alias;
    private \Graphpinator\Parser\Value\ArgumentValueSet $arguments;
    private \Graphpinator\Normalizer\Directive\DirectiveSet $directives;
    private ?\Graphpinator\Normalizer\Field\FieldSet $children = null;
    private ?\Graphpinator\Type\Contract\TypeConditionable $typeCond = null;

    public function __construct(
        \Graphpinator\Parser\Field\Field $parserField,
        \Graphpinator\Type\Contract\NamedDefinition $parentType,
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions
    )
    {
        \assert($parentType instanceof \Graphpinator\Type\Contract\Outputable);

        $this->name = $parserField->getName();
        $this->alias = $parserField->getAlias()
            ?? $this->name;
        $this->arguments = $parserField->getArguments()
            ?? new \Graphpinator\Parser\Value\ArgumentValueSet([]);

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

        if ($parserField->getFields() instanceof \Graphpinator\Parser\Field\FieldSet) {
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

    public function getArguments() : \Graphpinator\Parser\Value\ArgumentValueSet
    {
        return $this->arguments;
    }

    public function getDirectives() : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function getFields() : ?\Graphpinator\Normalizer\Field\FieldSet
    {
        return $this->children;
    }

    public function getTypeCondition() : ?\Graphpinator\Type\Contract\TypeConditionable
    {
        return $this->typeCond;
    }

    public function applyFragmentTypeCondition(?\Graphpinator\Type\Contract\TypeConditionable $typeCond) : void
    {
        if (!$typeCond instanceof \Graphpinator\Type\Contract\TypeConditionable) {
            return;
        }

        if (!$this->typeCond instanceof \Graphpinator\Type\Contract\TypeConditionable) {
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
}
