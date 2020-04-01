<?php

declare(strict_types);

namespace Graphpinator\DI;

interface TypeResolver
{
    public function getType(string $name) : \Graphpinator\Type\Contract\NamedDefinition;
}
