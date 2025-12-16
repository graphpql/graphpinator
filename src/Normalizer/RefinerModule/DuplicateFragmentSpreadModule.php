<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\RefinerModule;

use Graphpinator\Normalizer\Selection\Field;
use Graphpinator\Normalizer\Selection\FragmentSpread;
use Graphpinator\Normalizer\Selection\InlineFragment;
use Graphpinator\Normalizer\Selection\SelectionSet;
use Graphpinator\Normalizer\Selection\SelectionVisitor;

final class DuplicateFragmentSpreadModule implements RefinerModule, SelectionVisitor
{
    private array $visitedFragments;
    private int $index;

    public function __construct(
        private SelectionSet $selections,
    )
    {
    }

    #[\Override]
    public function refine() : void
    {
        $this->visitedFragments = [];

        foreach ($this->selections as $index => $selection) {
            $this->index = $index;
            $selection->accept($this);
        }
    }

    #[\Override]
    public function visitField(Field $field) : mixed
    {
        return null;
    }

    #[\Override]
    public function visitFragmentSpread(
        FragmentSpread $fragmentSpread,
    ) : mixed
    {
        if (!\array_key_exists($fragmentSpread->getName(), $this->visitedFragments)) {
            $this->visitedFragments[$fragmentSpread->getName()] = true;

            return null;
        }

        /** Found identical fragment spread, we can safely exclude it */
        $this->selections->offsetUnset($this->index);

        return null;
    }

    #[\Override]
    public function visitInlineFragment(
        InlineFragment $inlineFragment,
    ) : mixed
    {
        $oldSelections = $this->selections;
        $oldIndex = $this->index;

        $this->selections = $inlineFragment->getSelections();

        foreach ($inlineFragment->getSelections() as $index => $selection) {
            $this->index = $index;
            $selection->accept($this);
        }

        $this->selections = $oldSelections;
        $this->index = $oldIndex;

        return null;
    }
}
