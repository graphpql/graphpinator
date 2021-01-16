<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface TypeConditionable extends \Graphpinator\Type\Contract\Scopable
{
    public function getName() : string;
}
