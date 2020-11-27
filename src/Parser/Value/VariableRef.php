<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class VariableRef implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    public function __construct(
        private string $varName
    ) {}

    public function getRawValue() : ?bool
    {
        throw new \Graphpinator\Exception\OperationNotSupported();
    }

    public function getVarName() : string
    {
        return $this->varName;
    }
}
