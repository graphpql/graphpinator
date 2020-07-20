<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use \Graphpinator\Resolver\OperationResult;

final class Operation
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Type $operation;
    private \Graphpinator\Normalizer\FieldSet $children;
    private \Graphpinator\Normalizer\Variable\VariableSet $variables;

    public function __construct(
        \Graphpinator\Type\Type $operation,
        \Graphpinator\Normalizer\FieldSet $children,
        \Graphpinator\Normalizer\Variable\VariableSet $variables
    )
    {
        $this->operation = $operation;
        $this->children = $children;
        $this->variables = $variables;
    }

    public function getFields() : \Graphpinator\Normalizer\FieldSet
    {
        return $this->children;
    }

    public function getVariables() : \Graphpinator\Normalizer\Variable\VariableSet
    {
        return $this->variables;
    }

    public function execute(array $variables) : OperationResult
    {
        $data = $this->operation->resolve(
            $this->children->applyVariables(new \Graphpinator\Resolver\VariableValueSet($this->variables, $variables)),
            \Graphpinator\Resolver\FieldResult::fromRaw($this->operation, null),
        );

        return new \Graphpinator\Resolver\OperationResult($data);
    }
}
