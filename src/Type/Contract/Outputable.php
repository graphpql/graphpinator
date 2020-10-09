<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface Outputable extends \Graphpinator\Type\Contract\Definition
{
    public function createResolvedValue($rawValue) : \Graphpinator\Value\ResolvedValue;
}
