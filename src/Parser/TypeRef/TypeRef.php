<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\TypeRef;

interface TypeRef
{
    public function normalize(\Graphpinator\Type\Container\Container $typeContainer) : \Graphpinator\Type\Contract\Definition;
}
