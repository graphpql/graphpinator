<?php

declare(strict_types = 1);

namespace PGQL\Type\Contract;

interface Inputable extends \PGQL\Type\Contract\Instantiable
{
    public function applyDefaults($value);
}
