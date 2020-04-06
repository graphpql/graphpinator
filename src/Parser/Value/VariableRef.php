<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class VariableRef implements Value
{
    use \Nette\SmartObject;

    private string $varName;

    public function __construct(string $name)
    {
        $this->varName = $name;
    }

    public function getRawValue(): void
    {
        throw new \Exception('Invalid state.');
    }

    public function applyVariables(\Graphpinator\Request\VariableValueSet $variables) : Value
    {
        if ($variables->offsetExists($this->varName)) {
            $value = $variables[$this->varName];

            return new Literal($value->getRawValue());
        }

        throw new \Exception('Unknown variable.');
    }
}
