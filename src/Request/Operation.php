<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class Operation
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Type $operation;
    private \Graphpinator\Request\FieldSet $children;
    private \Graphpinator\Request\Variable\VariableSet $variables;

    public function __construct(
        \Graphpinator\Type\Type $operation,
        \Graphpinator\Request\FieldSet $children,
        \Graphpinator\Request\Variable\VariableSet $variables
    ) {
        $this->operation = $operation;
        $this->children = $children;
        $this->variables = $variables;
    }

    public function getChildren() : \Graphpinator\Request\FieldSet
    {
        return $this->children;
    }

    public function getVariables() : \Graphpinator\Request\Variable\VariableSet
    {
        return $this->variables;
    }

    public function execute(\Infinityloop\Utils\Json $variables) : ExecutionResult
    {
        $data = $this->operation->resolveFields(
            $this->children->applyVariables(new VariableValueSet($this->variables, $variables)),
            \Graphpinator\Request\ResolveResult::fromRaw($this->operation, null)
        );

        return new ExecutionResult($data);
    }
}
