<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class FinalizedRequest
{
    use \Nette\SmartObject;

    private \Graphpinator\Normalizer\Operation\Operation $operation;
    private \Graphpinator\Normalizer\VariableValueSet $variables;

    public function __construct(
        \Graphpinator\Normalizer\Operation\OperationSet $operationSet,
        \stdClass $variables,
        ?string $operationName,
    )
    {
        $this->operation = $operationName === null
            ? $operationSet->current()
            : $operationSet->offsetGet($operationName);
        $this->variables = new \Graphpinator\Normalizer\VariableValueSet($this->operation->getVariables(), $variables);
    }

    public function execute() : \Graphpinator\Result
    {
        return $this->operation->resolve($this->variables);
    }

    public function getOperation() : \Graphpinator\Normalizer\Operation\Operation
    {
        return $this->operation;
    }

    public function getVariables() : \Graphpinator\Normalizer\VariableValueSet
    {
        return $this->variables;
    }
}
