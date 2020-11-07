<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

/**
 * @method Variable current() : object
 * @method Variable offsetGet($offset) : object
 */
final class VariableSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = Variable::class;

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
