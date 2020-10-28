<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class Request
{
    use \Nette\SmartObject;

    private string $query;
    private \stdClass $variables;
    private ?string $operationName;

    public function __construct(string $query, ?\stdClass $variables = null, ?string $operationName = null)
    {
        $this->query = $query;
        $this->variables = $variables
            ?? new \stdClass();
        $this->operationName = $operationName;
    }

    public function getQuery() : string
    {
        return $this->query;
    }

    public function getVariables() : ?\stdClass
    {
        return $this->variables;
    }

    public function getOperationName() : ?string
    {
        return $this->operationName;
    }
}
