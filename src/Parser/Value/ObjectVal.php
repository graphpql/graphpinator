<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class ObjectVal implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    public function __construct(
        private \stdClass $value
    ) {}

    public function getRawValue() : \stdClass
    {
        $return = new \stdClass();

        foreach ($this->value as $key => $value) {
            \assert($value instanceof Value);

            $return->{$key} = $value->getRawValue();
        }

        return $return;
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : Value
    {
        $return = new \stdClass();

        foreach ($this->value as $key => $value) {
            \assert($value instanceof Value);

            $return->{$key} = $value->applyVariables($variables);
        }

        return new self($return);
    }

    public function isSame(Value $compare) : bool
    {
        if (!$compare instanceof self) {
            return false;
        }

        $secondObject = $compare->getValue();

        if (\count((array) $secondObject) !== \count((array) $this->value)) {
            return false;
        }

        foreach ($this->value as $key => $value) {
            \assert($value instanceof Value);

            if (!\property_exists($secondObject, $key) ||
                !$value->isSame($secondObject->{$key})) {
                return false;
            }
        }

        return true;
    }
}
