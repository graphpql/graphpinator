<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class ParseResult
{
    use \Nette\SmartObject;

    private Operation $operation;
    private array $fragments;

    public function __construct(Operation $operation, array $fragments)
    {
        $this->operation = $operation;
        $this->fragments = $fragments;
    }

    public function getOperation() : Operation
    {
        return $this->operation;
    }

    public function getFragments() : array
    {
        return $this->fragments;
    }
}
