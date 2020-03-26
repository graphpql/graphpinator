<?php

declare(strict_types = 1);

namespace PGQL\Type;

interface Inputable extends Definition
{
    public function applyDefaults($value);
}
