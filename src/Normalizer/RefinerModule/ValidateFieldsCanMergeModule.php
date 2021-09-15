<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\RefinerModule;

final class ValidateFieldsCanMergeModule implements RefinerModule, \Graphpinator\Normalizer\Selection\SelectionVisitor
{
    use \Nette\SmartObject;

    private array $fieldsForName;
    private ?\Graphpinator\Typesystem\Contract\TypeConditionable $contextType;

    public function __construct(
        private \Graphpinator\Normalizer\Selection\SelectionSet $selections,
    )
    {
    }

    public function refine() : void
    {
        $this->fieldsForName = [];
        $this->contextType = null;

        foreach ($this->selections as $selection) {
            $selection->accept($this);
        }
    }

    public function visitField(\Graphpinator\Normalizer\Selection\Field $field) : mixed
    {
        if (\array_key_exists($field->getOutputName(), $this->fieldsForName)) {
            foreach ($this->fieldsForName[$field->getOutputName()] as $fieldForName) {
                \assert($fieldForName instanceof FieldForName);

                if (self::canOccurTogether($this->contextType, $fieldForName->fragmentType)) {
                    $this->validateConflictingFields($field, $fieldForName->field);
                }
            }

            return null;
        }

        $this->fieldsForName[$field->getOutputName()] = [
            new FieldForName($field, $this->contextType),
        ];

        return null;
    }

    public function visitFragmentSpread(
        \Graphpinator\Normalizer\Selection\FragmentSpread $fragmentSpread,
    ) : mixed
    {
        $this->processFragment($fragmentSpread);

        return null;
    }

    public function visitInlineFragment(
        \Graphpinator\Normalizer\Selection\InlineFragment $inlineFragment,
    ) : mixed
    {
        $this->processFragment($inlineFragment);

        return null;
    }

    private static function canOccurTogether(
        ?\Graphpinator\Typesystem\Contract\TypeConditionable $typeA,
        ?\Graphpinator\Typesystem\Contract\TypeConditionable $typeB,
    ) : bool
    {
        return $typeA === null
            || $typeB === null
            || $typeA->isInstanceOf($typeB) // one is instanceof other
            || $typeB->isInstanceOf($typeA)
            || !($typeA instanceof \Graphpinator\Typesystem\Type) // one is not an object type (final typesystem object)
            || !($typeB instanceof \Graphpinator\Typesystem\Type);
    }

    private function processFragment(
        \Graphpinator\Normalizer\Selection\InlineFragment|\Graphpinator\Normalizer\Selection\FragmentSpread $fragment,
    ) : void
    {
        $oldSelections = $this->selections;
        $this->selections = $fragment->getSelections();
        $oldContextType = $this->contextType;
        $this->contextType = $fragment->getTypeCondition()
            ?? $this->contextType;

        foreach ($fragment->getSelections() as $selection) {
            $selection->accept($this);
        }

        $this->selections = $oldSelections;
        $this->contextType = $oldContextType;
    }

    private function validateConflictingFields(
        \Graphpinator\Normalizer\Selection\Field $field,
        \Graphpinator\Normalizer\Selection\Field $conflict,
    ) : void
    {
        $fieldReturnType = $field->getField()->getType();
        $conflictReturnType = $conflict->getField()->getType();

        /** Fields must have same response shape (return type) */
        if (!$fieldReturnType->isInstanceOf($conflictReturnType) ||
            !$conflictReturnType->isInstanceOf($fieldReturnType)) {
            throw new \Graphpinator\Normalizer\Exception\ConflictingFieldType();
        }

        /** Fields have same alias, but refer to a different field */
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

        /** Fields are composite -> validate combined inner fields */
        if (!$conflict->getSelections() instanceof \Graphpinator\Normalizer\Selection\SelectionSet) {
            return;
        }

        $mergedSet = $conflict->getSelections()->merge($field->getSelections());
        $refiner = new self($mergedSet);
        $refiner->refine();
    }
}
