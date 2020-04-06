<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\TypeRef;

interface TypeRef
{
    public function resolve(\Graphpinator\Type\Resolver $resolver) : \Graphpinator\Type\Contract\Definition;
}
