<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class EnumValue extends LeafValue
{
    public function printValue() : string
    {
        return $this->rawValue;
    }

    public function getRawValue(bool $forResolvers = false) : string
    {
        return $this->rawValue;
    }
}
