<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use Graphpinator\Normalizer\Operation\Operation;

final class FinalizedRequest
{
    public function __construct(
        private Operation $operation,
    )
    {
    }

    public function getOperation() : Operation
    {
        return $this->operation;
    }
}
