<?php

declare(strict_types = 1);

namespace Graphpinator;

final class OperationRequest
{
    use \Nette\SmartObject;

    private \Graphpinator\Normalizer\Operation\Operation $operation;
    private \Graphpinator\Resolver\VariableValueSet $variables;

    public function __construct(
        \Graphpinator\Normalizer\Operation\OperationSet $operationSet,
        \stdClass $variables,
        ?string $operationName,
    )
    {
        $this->operation = $operationName === null
            ? $operationSet->current()
            : $operationSet->offsetGet($operationName);
        $this->variables = new \Graphpinator\Resolver\VariableValueSet($this->operation->getVariables(), $variables);
    }

    public function execute() : \Graphpinator\OperationResponse
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
