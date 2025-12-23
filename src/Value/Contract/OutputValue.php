<?php

declare(strict_types = 1);

namespace Graphpinator\Value\Contract;

interface OutputValue extends Value, \JsonSerializable
{
    #[\Override]
    public function jsonSerialize() : \stdClass|array|string|int|float|bool|null;
}
