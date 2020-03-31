<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Request;

final class Operation
{
    use \Nette\SmartObject;

    private string $operation;
    private ?string $name;
    private ?FieldSet $children;

    public function __construct(
        string $operation = 'query',
        ?string $name = null,
        ?FieldSet $children = null
    ) {
        $this->operation = $operation;
        $this->name = $name;
        $this->children = $children;
    }

    public function getOperation() : string
    {
        return $this->operation;
    }

    public function getName() : ?string
    {
        return $this->name;
    }

    public function getChildren() : ?FieldSet
    {
        return $this->children;
    }
}
