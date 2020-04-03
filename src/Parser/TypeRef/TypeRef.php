<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\TypeRef;

interface TypeRef
{
    public function resolve(\Graphpinator\DI\TypeResolver $resolver) : \Graphpinator\Type\Contract\Definition;
}
