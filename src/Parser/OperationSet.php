<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class OperationSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Operation::class;

    public function current() : Operation
    {
        return parent::current();
    }

    public function offsetGet($offset) : Operation
    {
        return parent::offsetGet($offset);
    }

    public function normalize(
        \Graphpinator\Type\Schema $schema,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions
    ) : \Graphpinator\Normalizer\OperationSet
    {
        $normalized = [];

        foreach ($this as $operation) {
            $normalized[] = $operation->normalize($schema, $fragmentDefinitions);
        }

        return new \Graphpinator\Normalizer\OperationSet($normalized);
    }

    protected function getKey(object $object) : ?string
    {
        return $object->getName();
    }
}
