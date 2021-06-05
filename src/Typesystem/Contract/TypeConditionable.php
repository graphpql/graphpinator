<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface TypeConditionable extends \Graphpinator\Typesystem\Contract\Outputable
{
    public function getName() : string;
}
