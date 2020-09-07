<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class VariableRef implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    private string $varName;

    public function __construct(string $name)
    {
        $this->varName = $name;
    }

    public function getRawValue() : void
    {
        throw new \Graphpinator\Exception\Parser\InvalidState();
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : Value
    {
        if ($variables->offsetExists($this->varName)) {
            $value = $variables[$this->varName];

            return new \Graphpinator\Parser\Value\Literal($value->getRawValue());
        }

        throw new \Graphpinator\Exception\Parser\UnknownVariable();
    }
}
