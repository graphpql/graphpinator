<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class NormalizedRequest
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Normalizer\Operation\OperationSet $operations,
    ) {}

    public function getOperations() : \Graphpinator\Normalizer\Operation\OperationSet
    {
        return $this->operations;
    }

    public function finalize(\stdClass $variables, ?string $operationName) : \Graphpinator\Normalizer\FinalizedRequest
    {
        return new \Graphpinator\Normalizer\FinalizedRequest($this->operations, $variables, $operationName);
    }
}
