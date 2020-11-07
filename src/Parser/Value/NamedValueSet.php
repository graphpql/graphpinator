<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

/**
 * @method NamedValue current() : object
 * @method NamedValue offsetGet($offset) : object
 */
final class NamedValueSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = NamedValue::class;

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
