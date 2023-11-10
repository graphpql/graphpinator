<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class FinalizedRequest
{
    public function __construct(
        private \Graphpinator\Normalizer\Operation\Operation $operation,
    )
    {
    }

    public function getOperation() : \Graphpinator\Normalizer\Operation\Operation
    {
        return $this->operation;
    }
}
