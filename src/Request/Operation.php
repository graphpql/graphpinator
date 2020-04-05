<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class Operation
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Type $operation;
    private FieldSet $children;

    public function __construct(
        \Graphpinator\Type\Type $operation,
        FieldSet $children
    ) {
        $this->operation = $operation;
        $this->children = $children;
    }

    public function getChildren() : FieldSet
    {
        return $this->children;
    }

    public function execute() : ExecutionResult
    {
        $data = $this->operation->resolveFields(
            $this->children,
            \Graphpinator\Field\ResolveResult::fromRaw($this->operation, null)
        );

        return new ExecutionResult($data);
    }
}
