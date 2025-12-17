<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\Type;
use Graphpinator\Value\Exception\InvalidValue;

final class TypeIntermediateValue implements ResolvedValue
{
    public function __construct(
        private Type $type,
        private mixed $rawValue,
    )
    {
        if (!$type->validateNonNullValue($rawValue)) {
            throw new InvalidValue($type, $rawValue, false);
        }
    }

    #[\Override]
    public function getRawValue(bool $forResolvers = false) : mixed
    {
        return $this->rawValue;
    }

    #[\Override]
    public function getType() : Type
    {
        return $this->type;
    }
}
