<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class ListVal implements Value
{
    use \Nette\SmartObject;

    private array $value;

    public function __construct(array $value)
    {
        $this->value = $value;
    }

    public function getRawValue() : array
    {
        $return = [];

        foreach ($this->value as $value) {
            \assert($value instanceof Value);

            $return[] = $value->getRawValue();
        }

        return $return;
    }

    public function applyVariables(\Graphpinator\Request\VariableValueSet $variables) : Value
    {
        $return = [];

        foreach ($this->value as $key => $value) {
            \assert($value instanceof Value);

            $return[$key] = $value->applyVariables($variables);
        }

        return new self($return);
    }
}
