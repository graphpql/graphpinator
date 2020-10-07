<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

interface ResolvableValue extends \JsonSerializable, Value
{
    /** @return string|int|float|bool|null|array|object */
    public function jsonSerialize();

    public function getType() : \Graphpinator\Type\Contract\Resolvable;
}
