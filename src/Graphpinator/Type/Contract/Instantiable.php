<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Contract;

interface Instantiable extends \Infinityloop\Graphpinator\Type\Contract\Definition
{
    public function createValue($rawValue) : \Infinityloop\Graphpinator\Value\ValidatedValue;
}
