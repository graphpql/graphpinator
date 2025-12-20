<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Refiner\Module;

use Graphpinator\Normalizer\Refiner\SelectionSetRefiner;
use Graphpinator\Normalizer\Selection\Field;
use Graphpinator\Normalizer\Selection\FragmentSpread;
use Graphpinator\Normalizer\Selection\InlineFragment;
use Graphpinator\Normalizer\Selection\SelectionSet;
use Graphpinator\Normalizer\Selection\SelectionVisitor;

final class DuplicateFieldModule implements RefinerModule, SelectionVisitor
{
    private array $fieldForName;
    private int $index;

    public function __construct(
        private SelectionSet $selections,
    )
    {
    }

    #[\Override]
    public function refine() : void
    {
        $this->fieldForName = [];

        foreach ($this->selections as $index => $selection) {
            $this->index = $index;
            $selection->accept($this);
        }
    }

    #[\Override]
    public function visitField(Field $field) : null
    {
        if (!\array_key_exists($field->outputName, $this->fieldForName)) {
            $this->fieldForName[$field->outputName] = $field;

            return null;
        }

        /** Merge duplicate field together */
        if ($field->children instanceof SelectionSet) {
            $conflict = $this->fieldForName[$field->outputName];
            \assert($conflict instanceof Field);
            \assert($conflict->children instanceof SelectionSet);
            $mergedSelectionSet = $conflict->children->merge($field->children);
            $refiner = new SelectionSetRefiner($mergedSelectionSet);
            $refiner->refine();
        }

        /** Exclude duplicate field */
        $this->selections->offsetUnset($this->index);

        return null;
    }

    #[\Override]
    public function visitFragmentSpread(FragmentSpread $fragmentSpread) : null
    {
        return null;
    }

    #[\Override]
    public function visitInlineFragment(InlineFragment $inlineFragment) : null
    {
        return null;
    }
}
