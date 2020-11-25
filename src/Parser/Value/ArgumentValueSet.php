<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

/**
 * @method \Graphpinator\Parser\Value\ArgumentValue current() : object
 * @method \Graphpinator\Parser\Value\ArgumentValue offsetGet($offset) : object
 */
final class ArgumentValueSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = ArgumentValue::class;

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
