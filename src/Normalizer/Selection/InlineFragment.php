<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Selection;

use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Typesystem\Contract\NamedType;

final readonly class InlineFragment implements Selection
{
    public function __construct(
        public SelectionSet $children,
        public DirectiveSet $directives,
        public ?NamedType $typeCondition,
    )
    {
    }

    #[\Override]
    public function accept(SelectionVisitor $visitor) : mixed
    {
        return $visitor->visitInlineFragment($this);
    }
}
