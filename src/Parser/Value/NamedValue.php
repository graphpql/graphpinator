<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class NamedValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Parser\Value\Value $value;
    private string $name;

    public function __construct(\Graphpinator\Parser\Value\Value $value, string $name)
    {
        $this->value = $value;
        $this->name = $name;
    }

    public function getValue() : \Graphpinator\Parser\Value\Value
    {
        return $this->value;
    }

    //@phpcs:ignore SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint
    public function getRawValue()
    {
        return $this->value->getRawValue();
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : self
    {
        return new self(
            $this->value->applyVariables($variables),
            $this->name,
        );
    }
}
