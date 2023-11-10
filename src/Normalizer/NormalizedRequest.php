<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class NormalizedRequest
{
    public function __construct(
        private \Graphpinator\Normalizer\Operation\OperationSet $operations,
    )
    {
    }

    public function getOperations() : \Graphpinator\Normalizer\Operation\OperationSet
    {
        return $this->operations;
    }
}
