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
        if (!$this->offsetExists($offset)) {
            throw new \Graphpinator\Exception\Normalizer\VariableNotDefined();
        }

        return $this->array[$offset];
    }

    protected function getKey($object) : string
    {
        return $object->getName();
    }
}
