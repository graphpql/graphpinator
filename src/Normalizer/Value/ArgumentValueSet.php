<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Value;

/**
 * @method \Graphpinator\Normalizer\Value\ArgumentValue current() : object
 * @method \Graphpinator\Normalizer\Value\ArgumentValue offsetGet($offset) : object
 */
final class ArgumentValueSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = \Graphpinator\Normalizer\Value\ArgumentValue::class;

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : void
    {
        foreach ($this as $value) {
            $value->applyVariables($variables);
        }
    }

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
