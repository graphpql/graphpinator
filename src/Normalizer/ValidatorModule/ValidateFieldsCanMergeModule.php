<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\ValidatorModule;

use Graphpinator\Normalizer\Exception\ConflictingFieldAlias;
use Graphpinator\Normalizer\Exception\ConflictingFieldArguments;
use Graphpinator\Normalizer\Exception\ConflictingFieldDirectives;
use Graphpinator\Normalizer\Exception\ConflictingFieldType;
use Graphpinator\Normalizer\Selection\Field;
use Graphpinator\Normalizer\Selection\FragmentSpread;
use Graphpinator\Normalizer\Selection\InlineFragment;
use Graphpinator\Normalizer\Selection\SelectionSet;
use Graphpinator\Normalizer\Selection\SelectionVisitor;
use Graphpinator\Typesystem\Contract\TypeConditionable;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\Visitor\IsInstanceOfVisitor;

final class ValidateFieldsCanMergeModule implements ValidatorModule, SelectionVisitor
{
    private array $fieldsForName;
    private ?TypeConditionable $contextType;

    public function __construct(
        private SelectionSet $selections,
        private bool $identity = true,
    )
    {
    }

    #[\Override]
    public function validate() : void
    {
        $this->fieldsForName = [];
        $this->contextType = null;

        foreach ($this->selections as $selection) {
            $selection->accept($this);
        }
    }

    #[\Override]
    public function visitField(Field $field) : mixed
    {
        if (\array_key_exists($field->getOutputName(), $this->fieldsForName)) {
            foreach ($this->fieldsForName[$field->getOutputName()] as $fieldForName) {
                \assert($fieldForName instanceof FieldForName);

                $canOccurTogether = false;
                $this->validateResponseShape($field, $fieldForName->field);

                if ($this->identity && self::canOccurTogether($this->contextType, $fieldForName->fragmentType)) {
                    $this->validateIdentity($field, $fieldForName->field);
                    $canOccurTogether = true;
                }

                $this->validateInnerFields($field, $fieldForName->field, $canOccurTogether);
            }

            return null;
        }

        $this->fieldsForName[$field->getOutputName()] = [
            new FieldForName($field, $this->contextType),
        ];

        return null;
    }

    #[\Override]
    public function visitFragmentSpread(
        FragmentSpread $fragmentSpread,
    ) : mixed
    {
        $this->processFragment($fragmentSpread);

        return null;
    }

    #[\Override]
    public function visitInlineFragment(
        InlineFragment $inlineFragment,
    ) : mixed
    {
        $this->processFragment($inlineFragment);

        return null;
    }

    private static function canOccurTogether(
        ?TypeConditionable $typeA,
        ?TypeConditionable $typeB,
    ) : bool
    {
        return $typeA === null
            || $typeB === null
            || $typeA->accept(new IsInstanceOfVisitor($typeB)) // one is instanceof other
            || $typeB->accept(new IsInstanceOfVisitor($typeA))
            || !($typeA instanceof Type) // one is not an object type (final typesystem object)
            || !($typeB instanceof Type);
    }

    private function processFragment(
        InlineFragment|FragmentSpread $fragment,
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

    private function validateResponseShape(
        Field $field,
        Field $conflict,
    ) : void
    {
        $fieldReturnType = $field->getField()->getType();
        $conflictReturnType = $conflict->getField()->getType();

        /** Fields must have same response shape (return type) */
        if (!$fieldReturnType->accept(new IsInstanceOfVisitor($conflictReturnType)) ||
            !$conflictReturnType->accept(new IsInstanceOfVisitor($fieldReturnType))) {
            throw new ConflictingFieldType();
        }
    }

    private function validateIdentity(
        Field $field,
        Field $conflict,
    ) : void
    {
        $fieldReturnType = $field->getField()->getType();
        $conflictReturnType = $conflict->getField()->getType();

        /** Fields must have same response shape (return type) */
        if (!$fieldReturnType->accept(new IsInstanceOfVisitor($conflictReturnType)) ||
            !$conflictReturnType->accept(new IsInstanceOfVisitor($fieldReturnType))) {
            throw new ConflictingFieldType();
        }

        /** Fields have same alias, but refer to a different field */
        if ($field->getName() !== $conflict->getName()) {
            throw new ConflictingFieldAlias();
        }

        /** Fields have different arguments */
        if (!$field->getArguments()->isSame($conflict->getArguments())) {
            throw new ConflictingFieldArguments();
        }

        /** Fields have different directives */
        if (!$field->getDirectives()->isSame($conflict->getDirectives())) {
            throw new ConflictingFieldDirectives();
        }
    }

    private function validateInnerFields(
        Field $field,
        Field $conflict,
        bool $identity,
    ) : void
    {
        /** Fields are composite -> validate combined inner fields */
        if (!$conflict->getSelections() instanceof SelectionSet) {
            return;
        }

        $mergedSet = (clone $conflict->getSelections())->merge($field->getSelections());
        $refiner = new self($mergedSet, $identity);
        $refiner->validate();
    }
}
