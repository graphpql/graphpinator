<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Field;

/**
 * @method \Graphpinator\Normalizer\Field\Field current() : object
 * @method \Graphpinator\Normalizer\Field\Field offsetGet($offset) : object
 */
final class FieldSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Field::class;

    protected array $fieldsForName = [];

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : void
    {
        foreach ($this as $field) {
            $field->applyVariables($variables);
        }
    }

    public function mergeFieldSet(
        \Graphpinator\Type\Contract\NamedDefinition $parentType,
        \Graphpinator\Normalizer\Field\FieldSet $fieldSet
    ) : void
    {
        foreach ($fieldSet as $field) {
            if (!\array_key_exists($field->getAlias(), $this->fieldsForName)) {
                $this->offsetSet(null, $field);

                continue;
            }

            $this->mergeConflictingField($parentType, $field);
        }
    }

    public function offsetSet($offset, $value) : void
    {
        \assert($value instanceof \Graphpinator\Normalizer\Field\Field);

        if (!\array_key_exists($value->getAlias(), $this->fieldsForName)) {
            $this->fieldsForName[$value->getAlias()] = [];
        }

        $this->fieldsForName[$value->getAlias()][] = $value;

        parent::offsetSet($offset, $value);
    }

    private function mergeConflictingField(
        \Graphpinator\Type\Contract\NamedDefinition $parentType,
        \Graphpinator\Normalizer\Field\Field $field
    ) : void
    {
        $fieldArguments = $field->getArguments();
        $fieldParentType = $field->getTypeCondition()
            ?? $parentType;
        $fieldReturnType = $fieldParentType->getField($field->getName())->getType();

        foreach ($this->fieldsForName[$field->getAlias()] as $conflict) {
            \assert($conflict instanceof \Graphpinator\Normalizer\Field\Field);

            $conflictArguments = $conflict->getArguments();
            $conflictParentType = $conflict->getTypeCondition()
                ?? $parentType;
            $conflictReturnType = $conflictParentType->getField($conflict->getName())->getType();

            /** Fields must have same response shape (type) */
            if (!$fieldReturnType->isInstanceOf($conflictReturnType) ||
                !$conflictReturnType->isInstanceOf($fieldReturnType)) {
                throw new \Graphpinator\Exception\Normalizer\ConflictingFieldType();
            }

            /** Fields have type conditions which can never occur together */
            if (!$conflictParentType->isInstanceOf($fieldParentType) &&
                !$fieldParentType->isInstanceOf($conflictParentType)) {
                continue;
            }

            /** Fields have same alias, but refer to different field */
            if ($field->getName() !== $conflict->getName()) {
                throw new \Graphpinator\Exception\Normalizer\ConflictingFieldAlias();
            }

            /** Fields have different arguments,
             * -> possible when type implementing some interface adds new optional argument
             * -> in this case the argument value must be the default one
             */
            foreach ($conflictArguments as $lhs) {
                if ($fieldArguments->offsetExists($lhs->getArgument()->getName())) {
                    if ($lhs->getValue()->isSame($fieldArguments[$lhs->getArgument()->getName()]->getValue())) {
                        continue;
                    }

                    throw new \Graphpinator\Exception\Normalizer\ConflictingFieldArguments();
                }

                if ($lhs->getValue()->isSame($lhs->getArgument()->getDefaultValue())) {
                    continue;
                }

                throw new \Graphpinator\Exception\Normalizer\ConflictingFieldArguments();
            }

            foreach ($fieldArguments as $lhs) {
                if ($conflictArguments->offsetExists($lhs->getArgument()->getName())) {
                    continue;
                }

                if ($lhs->getValue()->isSame($lhs->getArgument()->getDefaultValue())) {
                    continue;
                }

                throw new \Graphpinator\Exception\Normalizer\ConflictingFieldArguments();
            }

            /** Fields are composite -> continue to children */
            if ($conflict->getFields() instanceof self) {
                $conflict->getFields()->mergeFieldSet($conflictReturnType, $field->getFields());
            }

            return;
        }

        /** Response shape is satisfied and no conflicting field can occur at the same time */
        $this->offsetSet(null, $field);
    }
}
