<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

interface Component
{
    public function accept(\Graphpinator\Typesystem\ComponentVisitor $visitor) : mixed;
}
