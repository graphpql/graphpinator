<?php

declare(strict_types = 1);

namespace Graphpinator\Value\Contract;

use Graphpinator\Typesystem\Contract\Type;

interface Value
{
    public function getRawValue() : mixed; // @phpcs:ignore

    public function getType() : Type;
}
