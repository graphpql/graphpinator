<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Operation;

final class Operation
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Type $operation;
    private \Graphpinator\Normalizer\FieldSet $children;
    private \Graphpinator\Normalizer\Variable\VariableSet $variables;
    private ?string $name;

    public function __construct(
        \Graphpinator\Type\Type $operation,
        \Graphpinator\Normalizer\FieldSet $children,
        \Graphpinator\Normalizer\Variable\VariableSet $variables,
        ?string $name
    )
    {
        $this->operation = $operation;
        $this->children = $children;
        $this->variables = $variables;
        $this->name = $name;
    }

    public function getType() : \Graphpinator\Type\Type
    {
        return $this->operation;
    }

    public function getFields() : \Graphpinator\Normalizer\FieldSet
    {
        return $this->children;
    }

    public function getVariables() : \Graphpinator\Normalizer\Variable\VariableSet
    {
        return $this->variables;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function resolve(\Graphpinator\Resolver\VariableValueSet $variables) : \Graphpinator\Resolver\OperationResult
    {
        $data = $this->operation->resolve(
            $this->children->applyVariables($variables),
            new \Graphpinator\Value\FieldValue($this->operation, null),
        );

        return new \Graphpinator\Resolver\OperationResult($data);
    }
}
