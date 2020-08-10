<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class NamedValueSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = NamedValue::class;

    public function current() : NamedValue
    {
        return parent::current();
    }

    public function offsetGet($offset) : NamedValue
    {
        return parent::offsetGet($offset);
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : self
    {
        $values = [];

        foreach ($this as $value) {
            $values[] = $value->applyVariables($variables);
        }

        return new self($values);
    }

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
