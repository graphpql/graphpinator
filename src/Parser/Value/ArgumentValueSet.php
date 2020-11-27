<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

/**
 * @method \Graphpinator\Parser\Value\ArgumentValue current() : object
 * @method \Graphpinator\Parser\Value\ArgumentValue offsetGet($offset) : object
 */
final class ArgumentValueSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = \Graphpinator\Parser\Value\ArgumentValue::class;

    public function normalize(
        \Graphpinator\Argument\ArgumentSet $argumentSet,
        \Graphpinator\Container\Container $typeContainer,
    ) : \Graphpinator\Normalizer\Value\ArgumentValueSet
    {
        $values = [];

        foreach ($this as $value) {
            $values[] = $value->normalize($typeContainer);
        }

        return new \Graphpinator\Normalizer\Value\ArgumentValueSet($values);
    }

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
