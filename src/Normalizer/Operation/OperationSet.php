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

    public function createRequest(?string $operationName, \stdClass $variables) : \Graphpinator\ParsedRequest
    {
        $operation = $operationName === null
            ? $this->current()
            : $this->offsetGet($operationName);

        return new \Graphpinator\ParsedRequest($operation, $variables);
    }

    protected function getKey(object $object) : ?string
    {
        return $object->getName();
    }
}
