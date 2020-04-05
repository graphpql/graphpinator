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

    public function normalize(\Graphpinator\Value\ValidatedValueSet $variables) : Literal
    {
        if ($variables->offsetExists($this->varName)) {
            return new Literal($variables->offsetGet($this->varName)->getRawValue());
        }

        throw new \Exception('Unknown variable');
    }

    public function getRawValue(): void
    {
        throw new \Exception();
    }
}
