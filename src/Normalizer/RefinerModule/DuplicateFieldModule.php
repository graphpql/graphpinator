<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\RefinerModule;

use \Graphpinator\Normalizer\Selection\SelectionSet;

final class DuplicateFieldModule implements RefinerModule, \Graphpinator\Normalizer\Selection\SelectionVisitor
{
    use \Nette\SmartObject;

    private array $fieldForName;
    private int $index;

    public function __construct(
        private SelectionSet $selections,
    )
    {
    }

    public function refine() : void
    {
        $this->fieldForName = [];

        foreach ($this->selections as $index => $selection) {
            $this->index = $index;
            $selection->accept($this);
        }
    }

    public function visitField(\Graphpinator\Normalizer\Selection\Field $field) : mixed
    {
        if (!\array_key_exists($field->getOutputName(), $this->fieldForName)) {
            $this->fieldForName[$field->getOutputName()] = $field;

            return null;
        }

        /** Merge duplicate field together */
        if ($field->getSelections() instanceof SelectionSet) {
            $conflict = $this->fieldForName[$field->getOutputName()];
            \assert($conflict instanceof \Graphpinator\Normalizer\Selection\Field);
            \assert($conflict->getSelections() instanceof SelectionSet);
            $mergedSelectionSet = $conflict->getSelections()->merge($field->getSelections());
            $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($mergedSelectionSet);
            $refiner->refine();
        }

        /** Exclude duplicate field */
        $this->selections->offsetUnset($this->index);

        return null;
    }

    public function visitFragmentSpread(
        \Graphpinator\Normalizer\Selection\FragmentSpread $fragmentSpread,
    ) : mixed
    {
        return null;
    }

    public function visitInlineFragment(
        \Graphpinator\Normalizer\Selection\InlineFragment $inlineFragment,
    ) : mixed
    {
        return null;
    }
}
