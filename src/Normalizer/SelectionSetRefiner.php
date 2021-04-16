<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class SelectionSetRefiner implements \Graphpinator\Normalizer\Selection\SelectionVisitor
{
    private array $refinedSet = [];
    private array $fieldsForName = [];

    public function __construct(
        private \Graphpinator\Normalizer\Selection\SelectionSet $selections,
    ) {}

    public function refine() : \Graphpinator\Normalizer\Selection\SelectionSet
    {
        $this->refinedSet = [];
        $this->fieldsForName = [];

        foreach ($this->selections as $selection) {
            $selection->accept($this);
        }

        return new \Graphpinator\Normalizer\Selection\SelectionSet($this->refinedSet);
    }

    public function visitField(\Graphpinator\Normalizer\Selection\Field $field) : mixed
    {
        if (!\array_key_exists($field->getOutputName(), $this->fieldsForName)) {
            $this->refinedSet[] = $field;
            $this->fieldsForName[$field->getOutputName()] = [$field];

            return;
        }

        $fieldArguments = $field->getArguments();
        $fieldReturnType = $field->getField()->getType();

        foreach ($this->fieldsForName[$field->getOutputName()] as $conflict) {
            \assert($conflict instanceof \Graphpinator\Normalizer\Selection\Field);

            $conflictReturnType = $conflict->getField()->getType();

            /** Fields must have same response shape (return type) */
            if (!$fieldReturnType->isInstanceOf($conflictReturnType) ||
                !$conflictReturnType->isInstanceOf($fieldReturnType)) {
                throw new \Graphpinator\Normalizer\Exception\ConflictingFieldType();
            }

            /** Fields have type conditions which can never occur together */
            if (!$conflictParentType->isInstanceOf($scopeType) &&
                !$scopeType->isInstanceOf($conflictParentType)) {
                continue;
            }

            /** Fields have same alias, but refer to different field */
            if ($field->getName() !== $conflict->getName()) {
                throw new \Graphpinator\Normalizer\Exception\ConflictingFieldAlias();
            }

            /** Fields have different arguments */
            if (!$fieldArguments->isSame($conflict->getArguments())) {
                throw new \Graphpinator\Normalizer\Exception\ConflictingFieldArguments();
            }

            /** Fields are composite -> continue to children */
            if ($conflict->getFields() instanceof \Graphpinator\Normalizer\Selection\SelectionSet) {
                $conflict->getFields()->mergeFieldSet($conflictReturnType, $field->getFields());
            }

            /** Found identical conflict = we can safely exclude it */
            return;
        }

    }

    public function visitFragmentSpread(\Graphpinator\Normalizer\Selection\FragmentSpread $fragmentSpread) : mixed
    {
        // TODO: Implement visitFragmentSpread() method.
    }

    public function visitInlineFragment(\Graphpinator\Normalizer\Selection\InlineFragment $inlineFragment) : mixed
    {
        if ($inlineFragment->getTypeCondition() instanceof \Graphpinator\Type\Contract\TypeConditionable) {

        }
    }
}
