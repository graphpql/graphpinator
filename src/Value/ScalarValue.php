<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ScalarValue extends LeafValue
{
    public function printValue() : string
    {
        return \json_encode($this->rawValue, \JSON_THROW_ON_ERROR |
            \JSON_UNESCAPED_UNICODE |
            \JSON_UNESCAPED_SLASHES |
            \JSON_PRESERVE_ZERO_FRACTION
        );
    }
}
