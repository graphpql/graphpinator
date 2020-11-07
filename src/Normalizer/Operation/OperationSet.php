<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Operation;

/**
 * @method \Graphpinator\Normalizer\Operation\Operation current() : object
 * @method \Graphpinator\Normalizer\Operation\Operation offsetGet($offset) : object
 */
final class OperationSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = Operation::class;

    public function createRequest(?string $operationName, \stdClass $variables) : \Graphpinator\ParsedRequest
    {
        $operation = $operationName === null
            ? $this->current()
            : $this->offsetGet($operationName);

        return new \Graphpinator\ParsedRequest($operation, $variables);
    }

    protected function getKey(object $object) : string
    {
        \assert($object instanceof Operation);

        return $object->getName()
            ?? '';
    }
}
