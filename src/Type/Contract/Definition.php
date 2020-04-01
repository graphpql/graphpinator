<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface Definition
{
    public function getNamedType() : \Graphpinator\Type\Contract\NamedDefinition;

    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool;

    public function isInputable() : bool;

    public function isOutputable() : bool;

    public function isInstantiable() : bool;

    public function isResolvable() : bool;
}
