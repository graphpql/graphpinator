<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

interface InputedValue extends Value
{
    public function printValue() : string;

    public function getType() : \Graphpinator\Type\Contract\Inputable;
}
