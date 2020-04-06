<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

interface Resolver
{
    public function getType(string $name) : \Graphpinator\Type\Contract\NamedDefinition;

    public function getSchema() : \Graphpinator\Type\Schema;
}
