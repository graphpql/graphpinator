<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

/**
 * Helper interface to mark Type, Interface and Union with ability to be referenced in type conditions.
 */
interface TypeConditionable extends Type
{
    public function getName() : string;
}
