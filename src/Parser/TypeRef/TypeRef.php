<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\TypeRef;

interface TypeRef
{
    public function normalize(\Graphpinator\Container\Container $typeContainer) : \Graphpinator\Type\Contract\Definition;

    public function print() : string;
}
