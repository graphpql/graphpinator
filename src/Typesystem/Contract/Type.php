<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

interface Type
{
    public function accept(\Graphpinator\Typesystem\Contract\TypeVisitor $visitor) : mixed;

    public function getNamedType() : \Graphpinator\Type\Contract\NamedType;

    public function getShapingType() : self;

    public function printName() : string;

    public function isInstanceOf(self $type) : bool;

    public function isInputable() : bool;
}
