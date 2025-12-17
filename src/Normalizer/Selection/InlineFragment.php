<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Selection;

use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Contract\NamedType;

final class InlineFragment implements Selection
{
    public function __construct(
        private SelectionSet $children,
        private DirectiveSet $directives,
        private ?NamedType $typeCondition,
    )
    {
    }

    public function getSelections() : SelectionSet
    {
        return $this->children;
    }

    public function getTypeCondition() : ?NamedType
    {
        return $this->typeCondition;
    }

    public function getDirectives() : DirectiveSet
    {
        return $this->directives;
    }

    #[\Override]
    public function applyVariables(VariableValueSet $variables) : void
    {
        $this->children->applyVariables($variables);
    }

    #[\Override]
    public function accept(SelectionVisitor $visitor) : mixed
    {
        return $visitor->visitInlineFragment($this);
    }
}
