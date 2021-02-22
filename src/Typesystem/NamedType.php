<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

interface NamedType extends Type, Entity
{
    public function accept(\Graphpinator\Typesystem\NamedTypeVisitor $visitor) : mixed;
}
