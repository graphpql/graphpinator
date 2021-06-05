<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface NamedType extends \Graphpinator\Typesystem\Contract\Type, \Graphpinator\Typesystem\Contract\Entity
{
    public function accept(\Graphpinator\Typesystem\Contract\NamedTypeVisitor $visitor) : mixed;
}
