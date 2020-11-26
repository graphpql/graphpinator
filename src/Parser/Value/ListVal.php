<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class ListVal implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    public function __construct(
        private array $value
    ) {}

    public function getRawValue() : array
    {
        $return = [];

        foreach ($this->value as $value) {
            \assert($value instanceof Value);

            $return[] = $value->getRawValue();
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

            if (!$value->isSame($secondArray[$key])) {
                return false;
            }
        }

        return true;
    }
}
