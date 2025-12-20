<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Selection;

use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Typesystem\Contract\NamedType;

final readonly class FragmentSpread implements Selection
{
    public function __construct(
        public string $name,
        public SelectionSet $children,
        public DirectiveSet $directives,
        public NamedType $typeCondition,
    )
    {
    }

    #[\Override]
    public function accept(SelectionVisitor $visitor) : mixed
    {
        return $visitor->visitFragmentSpread($this);
    }
}
