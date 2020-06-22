<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class Literal implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    //@phpcs:ignore SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint
    public function getRawValue()
    {
        return $this->value;
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : Value
    {
        return $this;
    }
}
