<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

interface InputableValue extends Value
{
    public function printValue() : string;

    public function getType() : \Graphpinator\Type\Contract\Inputable;
}
