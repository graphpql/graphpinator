<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

interface ResolvedValue extends \Graphpinator\Value\Value
{
    public function getType() : \Graphpinator\Type\Contract\Outputable;
}
