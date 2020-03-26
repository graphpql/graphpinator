<?php

declare(strict_types = 1);

namespace PGQL\Type;

abstract class AbstractDefinition extends NamedDefinition
{
    abstract public function isImplementedBy(Definition $definition) : bool;
}
