<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface Inputable extends \Graphpinator\Type\Contract\Definition
{
    public function applyDefaults($value);
}
