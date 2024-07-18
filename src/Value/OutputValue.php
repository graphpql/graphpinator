<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

interface OutputValue extends ResolvedValue, \JsonSerializable
{
    public function jsonSerialize() : \stdClass|array|string|int|float|bool|null;
}
