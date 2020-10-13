<?php

declare(strict_types = 1);

namespace Graphpinator;

final class ParsedRequest
{
    use \Nette\SmartObject;

    private \Graphpinator\Normalizer\Operation\Operation $operation;
    private \Graphpinator\Resolver\VariableValueSet $variables;

    public function __construct(
        \Graphpinator\Normalizer\Operation\Operation $operation,
        \stdClass $variables
    )
    {
        $this->operation = $operation;
        $this->variables = new \Graphpinator\Resolver\VariableValueSet($operation->getVariables(), $variables);
    }

    public function execute() : \Graphpinator\Response
    {
        return $this->operation->resolve($this->variables);
    }

    public function getOperation() : \Graphpinator\Normalizer\Operation\Operation
    {
        return $this->operation;
    }

    public function getVariables() : \Graphpinator\Resolver\VariableValueSet
    {
        return $this->variables;
    }
}
