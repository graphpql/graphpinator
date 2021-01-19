<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface Definition
{
    public function getTypeKind() : string;

    public function getNamedType() : \Graphpinator\Type\Contract\NamedDefinition;

    public function getShapingType() : \Graphpinator\Type\Contract\Definition;

    public function printName() : string;

    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool;

    public function isInputable() : bool;

    public function isOutputable() : bool;

    public function isResolvable() : bool;
}
