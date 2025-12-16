<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface Type
{
    public function accept(TypeVisitor $visitor) : mixed;
}
