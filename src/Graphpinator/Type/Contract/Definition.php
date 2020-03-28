<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Contract;

interface Definition
{
    public function getNamedType() : \Infinityloop\Graphpinator\Type\Contract\NamedDefinition;

    public function isInstanceOf(\Infinityloop\Graphpinator\Type\Contract\Definition $type) : bool;

    public function isInputable() : bool;

    public function isOutputable() : bool;

    public function isInstantiable() : bool;

    public function isResolvable() : bool;
}
