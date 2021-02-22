<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class FinalizedRequest
{
    use \Nette\SmartObject;

    private \Graphpinator\Normalizer\Operation\Operation $operation;

    public function __construct(
        \Graphpinator\Normalizer\Operation\OperationSet $operationSet,
        \stdClass $variables,
        ?string $operationName,
    )
    {
        $operation = $operationName === null
            ? $operationSet->current()
            : $operationSet->offsetGet($operationName);
        $variableSet = new \Graphpinator\Normalizer\VariableValueSet($operation->getVariables(), $variables);
        $operation->getFields()->applyVariables($variableSet);

        $this->operation = $operation;
    }

    public function execute() : \Graphpinator\Result
    {
        return $this->operation->resolve();
    }

    public function getOperation() : \Graphpinator\Normalizer\Operation\Operation
    {
        return $this->operation;
    }
}
