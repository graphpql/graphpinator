<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final readonly class Request
{
    public function __construct(
        public string $query,
        public \stdClass $variables = new \stdClass(),
        public ?string $operationName = null,
    )
    {
    }
}
