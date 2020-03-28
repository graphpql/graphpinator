<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Utils;

interface InterfaceImplementor
{
    public function getInterfaces() : InterfaceSet;

    public function implements(\Infinityloop\Graphpinator\Type\InterfaceType $interface) : bool;
}
