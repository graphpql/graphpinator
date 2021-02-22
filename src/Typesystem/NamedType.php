<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

interface NamedType extends \Graphpinator\Typesystem\Type, \Graphpinator\Typesystem\Entity
{
    public function accept(\Graphpinator\Typesystem\NamedTypeVisitor $visitor) : mixed;
}
