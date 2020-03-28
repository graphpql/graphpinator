<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Contract;

abstract class AbstractDefinition extends \Infinityloop\Graphpinator\Type\Contract\NamedDefinition
{
    abstract public function isImplementedBy(\Infinityloop\Graphpinator\Type\Contract\Definition $definition) : bool;
}
