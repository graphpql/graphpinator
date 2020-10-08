<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

interface OutputValue extends ResolvedValue, \JsonSerializable
{
    /** @return string|int|float|bool|null|array|object */
    public function jsonSerialize();
}
