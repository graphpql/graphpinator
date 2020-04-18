<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Variable;

final class VariableSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Variable::class;

    public function current() : Variable
    {
        return parent::current();
    }

    public function offsetGet($offset) : Variable
    {
        return parent::offsetGet($offset);
    }

    protected function getKey($object)
    {
        return $object->getName();
    }
}
