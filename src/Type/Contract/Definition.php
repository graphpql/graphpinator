<?php

declare(strict_types = 1);

namespace PGQL\Type\Contract;

interface Definition
{
    public function getNamedType() : \PGQL\Type\Contract\NamedDefinition;

    public function isInstanceOf(\PGQL\Type\Contract\Definition $type) : bool;
}
