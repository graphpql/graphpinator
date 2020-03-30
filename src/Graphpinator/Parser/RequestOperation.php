<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Parser;

final class RequestOperation
{
    use \Nette\SmartObject;

    private string $operation;
    private ?string $name;
    private ?RequestFieldSet $children;

    public function __construct(
        string $operation = 'query',
        ?string $name = null,
        ?RequestFieldSet $children = null
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

    public function getChildren() : ?RequestFieldSet
    {
        return $this->children;
    }
}
