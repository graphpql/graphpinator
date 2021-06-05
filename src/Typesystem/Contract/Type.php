<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface Type
{
    public function accept(\Graphpinator\Typesystem\Contract\TypeVisitor $visitor) : mixed;

    public function getNamedType() : \Graphpinator\Type\Contract\NamedDefinition;

    public function getShapingType() : \Graphpinator\Type\Contract\Definition;

    public function printName() : string;

    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool;

    public function isInputable() : bool;
}
