<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\Contract\Outputable;

interface ResolvedValue extends Value
{
    public function getType() : Outputable;
}
