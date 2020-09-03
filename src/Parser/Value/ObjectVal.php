<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class ObjectVal implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    private array $value;

    public function __construct(array $value)
    {
        $this->value = $value;
    }

    public function getValue() : array
    {
        return $this->value;
    }

    public function getRawValue() : array
    {
        $return = [];

        foreach ($this->value as $key => $value) {
            \assert($value instanceof Value);

            $return[$key] = $value->getRawValue();
        }

        return $return;
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : Value
    {
        $return = [];

        foreach ($this->value as $key => $value) {
            \assert($value instanceof Value);

            $return[$key] = $value->applyVariables($variables);
        }

        return new self($return);
    }

    public function isSame(Value $compare) : bool
    {
        if (!$compare instanceof self) {
            return false;
        }

        $secondArray = $compare->getValue();

        if (\count($secondArray) !== \count($this->value)) {
            return false;
        }

        foreach ($this->value as $key => $value) {
            \assert($value instanceof Value);

            if (!\array_key_exists($key, $secondArray) ||
                !$value->isSame($secondArray[$key])) {
                return false;
            }
        }

        return true;
    }
}
