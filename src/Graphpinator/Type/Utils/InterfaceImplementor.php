<?php

declare(strict_types = 1);

namespace PGQL\Type\Utils;

interface InterfaceImplementor
{
    public function getInterfaces() : InterfaceSet;

    public function implements(\PGQL\Type\InterfaceType $interface) : bool;
}
