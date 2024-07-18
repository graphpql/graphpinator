<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\Contract\Type;

interface Value
{
    public function getRawValue() : mixed;

    public function getType() : Type;
}
