<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\RefinerModule;

final class DuplicateFieldModule implements RefinerModule, \Graphpinator\Normalizer\Selection\SelectionVisitor
{
    use \Nette\SmartObject;

    private array $fieldForName;
    private int $currentIndex;

    public function __construct(
        private \Graphpinator\Normalizer\Selection\SelectionSet $selections,
    ) {}

    public function refine() : void
    {
        $this->fieldForName = [];

        foreach ($this->selections as $index => $selection) {
            $this->currentIndex = $index;
            $selection->accept($this);
        }
    }

    public function visitField(\Graphpinator\Normalizer\Selection\Field $field) : mixed
    {
        if (!\array_key_exists($field->getOutputName(), $this->fieldForName)) {
            $this->fieldForName[$field->getOutputName()] = $field;

            return null;
        }

        $conflict = $this->fieldForName[$field->getOutputName()];
        \assert($conflict instanceof \Graphpinator\Normalizer\Selection\Field);

        $fieldReturnType = $field->getField()->getType();
        $conflictReturnType = $conflict->getField()->getType();

        /** Fields must have same response shape (return type) */
        if (!$fieldReturnType->isInstanceOf($conflictReturnType) ||
            !$conflictReturnType->isInstanceOf($fieldReturnType)) {
            throw new \Graphpinator\Normalizer\Exception\ConflictingFieldType();
        }

        /** Fields have same alias, but refer to different field */
        if ($field->getName() !== $conflict->getName()) {
            throw new \Graphpinator\Normalizer\Exception\ConflictingFieldAlias();
        }

        /** Fields have different arguments */
        if (!$field->getArguments()->isSame($conflict->getArguments())) {
            throw new \Graphpinator\Normalizer\Exception\ConflictingFieldArguments();
        }

        /** Fields have different directives */
        if (!$field->getDirectives()->isSame($conflict->getDirectives())) {
            throw new \Graphpinator\Normalizer\Exception\ConflictingFieldDirectives();
        }

        /** Fields are composite -> combine and refine combined fields */
        if ($field->getSelections() instanceof \Graphpinator\Normalizer\Selection\SelectionSet) {
            $mergedSet = $field->getSelections()->merge($conflict->getSelections());
            $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($mergedSet, $field->getField()->getType());

            $conflict->setSelections($refiner->refine());
        }

        /** Found identical field, we can safely exclude it */
        $this->selections->offsetUnset($this->currentIndex);

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
