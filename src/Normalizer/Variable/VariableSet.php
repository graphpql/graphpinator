<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

use Infinityloop\Utils\ImplicitObjectMap;

/**
 * @method Variable current() : object
 * @method Variable offsetGet($offset) : object
 */
final class VariableSet extends ImplicitObjectMap
{
    protected const INNER_CLASS = Variable::class;

    #[\Override]
    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
