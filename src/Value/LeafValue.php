<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Exception\Value\InvalidValue;
use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Contract\LeafType;

abstract class LeafValue implements InputedValue, OutputValue
{
    public function __construct(
        protected LeafType $type,
        protected mixed $rawValue,
        bool $inputed,
    )
    {
        if (!$type->validateNonNullValue($rawValue)) {
            throw new InvalidValue($type->getName(), $rawValue, $inputed);
        }
    }

    #[\Override]
    public function getType() : LeafType
    {
        return $this->type;
    }

    #[\Override]
    public function jsonSerialize() : string|int|float|bool
    {
        return $this->rawValue;
    }

    #[\Override]
    public function applyVariables(VariableValueSet $variables) : void
    {
        // nothing here
    }

    #[\Override]
    public function resolveRemainingDirectives() : void
    {
        // nothing here
    }

    #[\Override]
    public function isSame(Value $compare) : bool
    {
        return $compare instanceof static
            && $this->rawValue === $compare->getRawValue();
    }
}
