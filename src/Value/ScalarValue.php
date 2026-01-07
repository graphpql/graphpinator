<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Value\Contract\InputedValue;
use Graphpinator\Value\Contract\InputedValueVisitor;
use Graphpinator\Value\Contract\OutputValue;
use Graphpinator\Value\Exception\InvalidValue;

final class ScalarValue implements InputedValue, OutputValue
{
    private mixed $rawValue;
    private mixed $resolverValue = null;
    private bool $hasResolverValue = false;

    public function __construct(
        private ScalarType $type,
        mixed $rawValue,
        bool $inputed,
    )
    {
        $this->rawValue = $inputed
            ? $type->validateAndCoerceInput($rawValue)
                ?? throw new InvalidValue($type, $rawValue, true)
            : $rawValue;
    }

    #[\Override]
    public function accept(InputedValueVisitor $visitor) : mixed
    {
        return $visitor->visitScalar($this);
    }

    #[\Override]
    public function getRawValue() : mixed
    {
        return $this->rawValue;
    }

    #[\Override]
    public function getType() : ScalarType
    {
        return $this->type;
    }

    #[\Override]
    public function jsonSerialize() : string|int|float|bool
    {
        return $this->type->coerceOutput($this->rawValue);
    }

    public function setResolverValue(mixed $value) : void
    {
        $this->hasResolverValue = true;
        $this->resolverValue = $value;
    }

    public function hasResolverValue() : bool
    {
        return $this->hasResolverValue;
    }

    public function getResolverValue() : mixed
    {
        return $this->resolverValue;
    }
}
