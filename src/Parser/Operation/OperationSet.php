<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Operation;

final class OperationSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Operation::class;

    public function current() : \Graphpinator\Parser\Operation\Operation
    {
        return parent::current();
    }

    public function offsetGet($offset) : \Graphpinator\Parser\Operation\Operation
    {
        if (!$this->offsetExists($offset)) {
            throw new \Graphpinator\Exception\Parser\OperationNotDefined();
        }

        return $this->array[$offset];
    }

    public function normalize(
        \Graphpinator\Type\Schema $schema,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions
    ) : \Graphpinator\Normalizer\Operation\OperationSet
    {
        $normalized = [];

        foreach ($this as $operation) {
            $normalized[] = $operation->normalize($schema, $fragmentDefinitions);
        }

        return new \Graphpinator\Normalizer\Operation\OperationSet($normalized);
    }

    protected function getKey(object $object) : ?string
    {
        return $object->getName();
    }
}
