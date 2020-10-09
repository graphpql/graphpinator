<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

interface Value
{
    /** @return string|int|float|bool|array|\stdClass|null */
    public function getRawValue();

    public function getType() : \Graphpinator\Type\Contract\Definition;
}
