<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\RefinerModule;

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

    public function refine() : void
    {
        foreach ($this->selections as $index => $selection) {
            $this->index = $index;
            $selection->accept($this);
        }
    }

    public function visitField(Field $field) : mixed
    {
        return null;
    }

    public function visitFragmentSpread(
        FragmentSpread $fragmentSpread,
    ) : mixed
    {
        $refiner = new self($fragmentSpread->getSelections());
        $refiner->refine();

        if ($fragmentSpread->getSelections()->count() === 0) {
            $this->selections->offsetUnset($this->index);
        }

        return null;
    }

    public function visitInlineFragment(
        InlineFragment $inlineFragment,
    ) : mixed
    {
        $refiner = new self($inlineFragment->getSelections());
        $refiner->refine();

        if ($inlineFragment->getSelections()->count() === 0) {
            $this->selections->offsetUnset($this->index);
        }

        return null;
    }
}
