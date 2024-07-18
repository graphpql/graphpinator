<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Operation;

use Infinityloop\Utils\ImplicitObjectMap;

/**
 * @method Operation current() : object
 * @method Operation offsetGet($offset) : object
 * @method Operation getFirst() : object
 */
final class OperationSet extends ImplicitObjectMap
{
    protected const INNER_CLASS = Operation::class;

    protected function getKey(object $object) : string
    {
        \assert($object instanceof Operation);

        return $object->getName()
            ?? '';
    }
}
