<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use Graphpinator\Normalizer\Operation\OperationSet;

final class NormalizedRequest
{
    public function __construct(
        private OperationSet $operations,
    )
    {
    }

    public function getOperations() : OperationSet
    {
        return $this->operations;
    }
}
