<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\TypeRef;

interface TypeRef
{
    public function create(\Graphpinator\DI\TypeResolver $resolver) : \Graphpinator\Type\Contract\Definition;
}
