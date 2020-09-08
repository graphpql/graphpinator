<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class FieldSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Field::class;

    protected array $fieldNames = [];

    public function current() : Field
    {
        return parent::current();
    }

    public function offsetGet($offset) : Field
    {
        if (!$this->offsetExists($offset)) {
            throw new \Graphpinator\Exception\Normalizer\FieldNotDefined();
        }

        return $this->array[$offset];
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : self
    {
        $fields = [];

        foreach ($this as $field) {
            $fields[] = $field->applyVariables($variables);
        }

        return new self($fields);
    }

    public function mergeFieldSet(\Graphpinator\Normalizer\FieldSet $fieldSet) : void
    {
        foreach ($fieldSet as $field) {
            if (!\array_key_exists($field->getAlias(), $this->fieldNames)) {
                $this->offsetSet(null, $field);

                continue;
            }

            $conflicts = $this->fieldNames[$field->getAlias()];

            foreach ($conflicts as $conflict) {
                \assert($conflict instanceof Field);

                if ($conflict->getTypeCondition() === null ||
                    $field->getTypeCondition() === null ||
                    $conflict->getTypeCondition() === $field->getTypeCondition()) {
                    $conflict->mergeField($field);

                    continue 2;
                }
            }

            $this->offsetSet(null, $field);
        }
    }

    protected function getKey(object $object) : ?string
    {
        \assert($object instanceof Field);

        if (!\array_key_exists($object->getAlias(), $this->fieldNames)) {
            $this->fieldNames[$object->getAlias()] = [];
        }

        $this->fieldNames[$object->getAlias()][] = $object;

        return parent::getKey($object);
    }
}
