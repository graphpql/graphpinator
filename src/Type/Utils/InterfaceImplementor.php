<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Utils;

interface InterfaceImplementor
{
    public function getInterfaces() : InterfaceSet;

    public function implements(\Graphpinator\Type\InterfaceType $interface) : bool;
}
