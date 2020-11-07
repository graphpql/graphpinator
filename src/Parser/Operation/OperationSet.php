<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Operation;

/**
 * @method \Graphpinator\Parser\Operation\Operation current() : object
 * @method \Graphpinator\Parser\Operation\Operation offsetGet($offset) : object
 */
final class OperationSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = Operation::class;

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

    protected function getKey(object $object) : string
    {
        return $object->getName()
            ?? '';
    }
}
