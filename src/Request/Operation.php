<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class Operation
{
    use \Nette\SmartObject;

    private string $type;
    private ?string $name;
    private ?FieldSet $children;

    public function __construct(
        string $operation = \Graphpinator\Tokenizer\OperationType::QUERY,
        ?string $name = null,
        ?FieldSet $children = null
    ) {
        $this->type = $operation;
        $this->name = $name;
        $this->children = $children;
    }

    public function getType() : string
    {
        return $this->type;
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
