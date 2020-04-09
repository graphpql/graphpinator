<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\TypeRef;

interface TypeRef
{
    public function resolve(\Graphpinator\Type\Container\Container $typeContainer) : \Graphpinator\Type\Contract\Definition;
}
