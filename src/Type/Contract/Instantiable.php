<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface Instantiable extends \Graphpinator\Type\Contract\Definition
{
    public function createValue($rawValue) : \Graphpinator\Resolver\Value\ValidatedValue;
}
