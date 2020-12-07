<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface Inputable extends \Graphpinator\Type\Contract\Definition
{
    public function createInputedValue(\stdClass|array|string|int|float|bool|null $rawValue) : \Graphpinator\Value\InputedValue;
}
