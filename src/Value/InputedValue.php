<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

interface InputedValue extends \Graphpinator\Value\Value
{
    public function printValue(bool $prettyPrint = false, int $indentLevel = 1) : string;

    public function getType() : \Graphpinator\Type\Contract\Inputable;
}
