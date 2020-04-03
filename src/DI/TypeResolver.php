<?php

declare(strict_types = 1);

namespace Graphpinator\DI;

interface TypeResolver
{
    public function getType(string $name) : \Graphpinator\Type\Contract\NamedDefinition;

    public function getSchema() : \Graphpinator\Type\Schema;
}
