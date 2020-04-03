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

    public function normalize(\Graphpinator\Value\ValidatedValueSet $variables)
    {
        if ($variables->offsetExists($this->varName)) {
            return $variables->offsetGet($this->varName)->getRawValue();
        }

        throw new \Exception('Unknown variable');
    }
}
