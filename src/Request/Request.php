<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class Request
{
    use \Nette\SmartObject;

    private \stdClass $variables;

    public function __construct(private string $query, ?\stdClass $variables = null, private ?string $operationName = null)
    {
        $this->variables = $variables
            ?? new \stdClass();
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
