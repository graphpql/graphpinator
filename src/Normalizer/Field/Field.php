<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Field;

final class Field
{
    use \Nette\SmartObject;

    private string $name;
    private string $alias;
    private \Graphpinator\Value\ArgumentValueSet $arguments;
    private \Graphpinator\Normalizer\Directive\DirectiveSet $directives;
    private ?\Graphpinator\Normalizer\Field\FieldSet $children = null;
    private ?\Graphpinator\Type\Contract\TypeConditionable $typeCond = null;

    public function __construct(
        \Graphpinator\Parser\Field\Field $parsed,
        \Graphpinator\Type\Contract\NamedDefinition $parentType,
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    )
    {
        \assert($parentType instanceof \Graphpinator\Type\Contract\Outputable);

        $this->name = $parsed->getName();
        $this->alias = $parsed->getAlias()
            ?? $this->name;

        $field = $parentType->getField($this->name);
        $fieldType = $field->getType()->getNamedType();

        $this->arguments = new \Graphpinator\Value\ArgumentValueSet(
            $parsed->getArguments() instanceof \Graphpinator\Parser\Value\ArgumentValueSet
                ? $parsed->getArguments()
                : new \Graphpinator\Parser\Value\ArgumentValueSet([]),
            $field,
            $variableSet,
        );
        $this->directives = new \Graphpinator\Normalizer\Directive\DirectiveSet(
            $parsed->getDirectives() instanceof \Graphpinator\Parser\Directive\DirectiveSet
                ? $parsed->getDirectives()
                : new \Graphpinator\Parser\Directive\DirectiveSet([], \Graphpinator\Directive\ExecutableDirectiveLocation::FIELD),
            $field->getType(),
            $typeContainer,
            $variableSet,
        );

        if ($parsed->getFields() instanceof \Graphpinator\Parser\Field\FieldSet) {
            $this->children = $parsed->getFields()->normalize($fieldType, $typeContainer, $fragmentDefinitions, $variableSet);
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

    public function getArguments() : \Graphpinator\Value\ArgumentValueSet
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

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
    {
        $this->arguments->applyVariables($variables);
        $this->directives->applyVariables($variables);
        $this->children?->applyVariables($variables);
    }
}
