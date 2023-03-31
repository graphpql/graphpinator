<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface TypeConditionable extends Outputable
{
    public function getName() : string;
}
