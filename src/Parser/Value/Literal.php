<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class Literal implements Value
{
    use \Nette\SmartObject;

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getRawValue()
    {
        return $this->value;
    }

    public function applyVariables(\Graphpinator\Request\VariableValueSet $variables) : Value
    {
        return $this;
    }
}
