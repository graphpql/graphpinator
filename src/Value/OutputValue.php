<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

interface OutputValue extends \Graphpinator\Value\ResolvedValue, \JsonSerializable
{
    /** @return string|int|float|bool|array|object|null */
    public function jsonSerialize();
}
