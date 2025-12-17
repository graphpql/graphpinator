<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\ScalarType;
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
        $this->rawValue = $type->validateAndCoerceInput($rawValue)
            ?? throw new InvalidValue($type, $rawValue, $inputed);
    }

    #[\Override]
    public function getRawValue(bool $forResolvers = false) : mixed
    {
        return ($forResolvers && $this->hasResolverValue)
            ? $this->resolverValue
            : $this->rawValue;
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

    #[\Override]
    public function printValue() : string
    {
        return \json_encode($this->jsonSerialize(), \JSON_THROW_ON_ERROR |
            \JSON_UNESCAPED_UNICODE |
            \JSON_UNESCAPED_SLASHES |
            \JSON_PRESERVE_ZERO_FRACTION);
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
        return $compare instanceof self
            && $this->rawValue === $compare->getRawValue();
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
