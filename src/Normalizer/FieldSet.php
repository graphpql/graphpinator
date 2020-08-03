<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class FieldSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Field::class;

    private \Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet $fragments;

    public function __construct(array $fields, ?\Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet $fragments = null)
    {
        parent::__construct($fields);

        $this->fragments = $fragments
            ?? new \Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet([]);
    }

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

    public function getFragments() : \Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet
    {
        return $this->fragments;
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : self
    {
        $fields = [];

        foreach ($this as $field) {
            $fields[] = $field->applyVariables($variables);
        }

        return new self(
            $fields,
            $this->fragments->applyVariables($variables),
        );
    }

    protected function getKey($object) : string
    {
        return $object->getName();
    }
}
