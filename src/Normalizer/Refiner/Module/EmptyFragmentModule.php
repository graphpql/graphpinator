<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Refiner\Module;

use Graphpinator\Normalizer\Selection\Field;
use Graphpinator\Normalizer\Selection\FragmentSpread;
use Graphpinator\Normalizer\Selection\InlineFragment;
use Graphpinator\Normalizer\Selection\SelectionSet;
use Graphpinator\Normalizer\Selection\SelectionVisitor;

final class EmptyFragmentModule implements RefinerModule, SelectionVisitor
{
    private int $index;

    public function __construct(
        private SelectionSet $selections,
    )
    {
    }

    #[\Override]
    public function refine() : void
    {
        foreach ($this->selections as $index => $selection) {
            $this->index = $index;
            $selection->accept($this);
        }
    }

    #[\Override]
    public function visitField(Field $field) : null
    {
        return null;
    }

    #[\Override]
    public function visitFragmentSpread(FragmentSpread $fragmentSpread) : null
    {
        $refiner = new self($fragmentSpread->children);
        $refiner->refine();

        if ($fragmentSpread->children->count() === 0) {
            $this->selections->offsetUnset($this->index);
        }

        return null;
    }

    #[\Override]
    public function visitInlineFragment(InlineFragment $inlineFragment) : null
    {
        $refiner = new self($inlineFragment->children);
        $refiner->refine();

        if ($inlineFragment->children->count() === 0) {
            $this->selections->offsetUnset($this->index);
        }

        return null;
    }
}
