<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

/**
 * @method \Graphpinator\Normalizer\Variable\Variable current() : object
 * @method \Graphpinator\Normalizer\Variable\Variable offsetGet($offset) : object
 */
final class VariableSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = Variable::class;

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
