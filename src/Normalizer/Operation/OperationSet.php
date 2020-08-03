<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Operation;

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

    public function execute(?string $operationName, array $variables) : \Graphpinator\Resolver\OperationResult
    {
        $operation = $operationName === null
            ? $this->current()
            : $this->offsetGet($operationName);

        return $operation->execute($variables);
    }

    protected function getKey(object $object) : string
    {
        return $object->getType()->getName();
    }
}
