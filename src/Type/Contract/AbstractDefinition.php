<?php

declare(strict_types = 1);

namespace PGQL\Type\Contract;

abstract class AbstractDefinition extends \PGQL\Type\Contract\NamedDefinition
{
    abstract public function isImplementedBy(\PGQL\Type\Contract\Definition $definition) : bool;
}
