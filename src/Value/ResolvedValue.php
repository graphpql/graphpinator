<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\Contract\Type;

interface ResolvedValue extends Value
{
    #[\Override]
    public function getType() : Type;
}
