<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

interface Value
{
    /** @return string|int|float|bool|null|array|\stdClass */
    public function getRawValue();

    public function getType() : \Graphpinator\Type\Contract\Definition;

    public function isNull() : bool;
}
