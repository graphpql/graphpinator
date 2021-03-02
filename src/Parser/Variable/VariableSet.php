<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Variable;

/**
 * @method \Graphpinator\Parser\Variable\Variable current() : object
 * @method \Graphpinator\Parser\Variable\Variable offsetGet($offset) : object
 */
final class VariableSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = Variable::class;

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
