<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\EnumType;

final class EnumValue extends LeafValue
{
    public function printValue() : string
    {
        return $this->rawValue;
    }

    public function getRawValue(bool $forResolvers = false) : string|object
    {
        \assert($this->type instanceof EnumType);

        if ($forResolvers && \is_string($this->type->getEnumClass())) {
            return \call_user_func([$this->type->getEnumClass(), 'from'], $this->rawValue);
        }

        return $this->rawValue;
    }
}
