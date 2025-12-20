<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use Graphpinator\Normalizer\Operation\Operation;

final readonly class FinalizedRequest
{
    public function __construct(
        public Operation $operation,
    )
    {
    }
}
