<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Selection;

final class FragmentSpread implements \Graphpinator\Normalizer\Selection\Selection
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Normalizer\Selection\SelectionSet $fields,
        private \Graphpinator\Normalizer\Directive\DirectiveSet $directives,
        private \Graphpinator\Type\Contract\TypeConditionable $typeCondition,
    ) {}

    public function getFields() : \Graphpinator\Normalizer\Selection\SelectionSet
    {
        return $this->fields;
    }

    public function getTypeCondition() : \Graphpinator\Type\Contract\TypeConditionable
    {
        return $this->typeCondition;
    }

    public function getDirectives() : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        return $this->directives;
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables): void
    {
        $this->fields->applyVariables($variables);
    }

    public function accept(SelectionVisitor $visitor) : mixed
    {
        return $visitor->visitFragmentSpread($this);
    }
}
