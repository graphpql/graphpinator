<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface TypeConditionable extends \Graphpinator\Type\Contract\Outputable
{
    public function getName() : string;
}
