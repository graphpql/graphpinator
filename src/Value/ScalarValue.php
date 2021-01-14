<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ScalarValue extends LeafValue
{
    public function printValue() : string
    {
        return \json_encode($this->rawValue, \JSON_THROW_ON_ERROR);
    }
}
