<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

abstract class LeafValue implements \Graphpinator\Value\InputedValue, \Graphpinator\Value\OutputValue
{
    public function __construct(
        protected \Graphpinator\Typesystem\Contract\LeafType $type,
        protected mixed $rawValue,
        bool $inputed,
    )
    {
        if (!$type->validateNonNullValue($rawValue)) {
            throw new \Graphpinator\Exception\Value\InvalidValue($type->getName(), $rawValue, $inputed);
        }
    }

    public function getType() : \Graphpinator\Typesystem\Contract\LeafType
    {
        return $this->type;
    }

    public function jsonSerialize() : string|int|float|bool
    {
        return $this->rawValue;
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
    {
        // nothing here
    }

    public function resolveRemainingDirectives() : void
    {
        // nothing here
    }

    public function isSame(Value $compare) : bool
    {
        return $compare instanceof static
            && $this->rawValue === $compare->getRawValue();
    }
}
