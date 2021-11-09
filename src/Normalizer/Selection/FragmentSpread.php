<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Selection;

use \Graphpinator\Normalizer\Directive\DirectiveSet;
use \Graphpinator\Typesystem\Contract\TypeConditionable;

final class FragmentSpread implements \Graphpinator\Normalizer\Selection\Selection
{
    use \Nette\SmartObject;

    public function __construct(
        private string $name,
        private SelectionSet $children,
        private DirectiveSet $directives,
        private TypeConditionable $typeCondition,
    )
    {
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getSelections() : SelectionSet
    {
        return $this->children;
    }

    public function getTypeCondition() : TypeConditionable
    {
        return $this->typeCondition;
    }

    public function getDirectives() : DirectiveSet
    {
        return $this->directives;
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
    {
        $this->children->applyVariables($variables);
    }

    public function accept(SelectionVisitor $visitor) : mixed
    {
        return $visitor->visitFragmentSpread($this);
    }
}
