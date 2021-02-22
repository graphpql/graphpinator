<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

interface Type
{
    public function accept(\Graphpinator\Typesystem\TypeVisitor $visitor) : mixed;
}
