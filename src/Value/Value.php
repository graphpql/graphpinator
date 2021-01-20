<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

interface Value
{
    public function getRawValue(bool $convertToObject = false) : mixed;

    public function getType() : \Graphpinator\Type\Contract\Definition;
}
