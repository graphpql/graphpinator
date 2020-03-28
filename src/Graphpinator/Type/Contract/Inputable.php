<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Contract;

interface Inputable extends \Infinityloop\Graphpinator\Type\Contract\Instantiable
{
    public function applyDefaults($value);
}
