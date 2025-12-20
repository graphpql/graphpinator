<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use Graphpinator\Normalizer\Operation\OperationSet;

final readonly class NormalizedRequest
{
    public function __construct(
        public OperationSet $operations,
    )
    {
    }
}
