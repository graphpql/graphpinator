<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class Request
{
    use \Nette\SmartObject;

    public function __construct(
        private string $query,
        private \stdClass $variables = new \stdClass(),
        private ?string $operationName = null,
    )
    {
    }

    public function getQuery() : string
    {
        return $this->query;
    }

    public function getVariables() : \stdClass
    {
        return $this->variables;
    }

    public function getOperationName() : ?string
    {
        return $this->operationName;
    }
}
