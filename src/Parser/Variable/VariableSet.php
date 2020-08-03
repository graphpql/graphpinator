<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Variable;

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
            throw new \Graphpinator\Exception\Parser\VariableNotDefined();
        }

        return $this->array[$offset];
    }

    public function normalize(\Graphpinator\Type\Container\Container $typeContainer) : \Graphpinator\Normalizer\Variable\VariableSet
    {
        $variables = [];

        foreach ($this as $variable) {
            $variables[] = $variable->normalize($typeContainer);
        }

        return new \Graphpinator\Normalizer\Variable\VariableSet($variables);
    }

    protected function getKey($object) : string
    {
        return $object->getName();
    }
}
