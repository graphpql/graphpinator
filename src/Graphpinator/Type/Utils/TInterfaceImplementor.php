<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Utils;

trait TInterfaceImplementor
{
    protected \Infinityloop\Graphpinator\Type\Utils\InterfaceSet $implements;

    public function getInterfaces() : InterfaceSet
    {
        return $this->implements;
    }

   public function implements(\Infinityloop\Graphpinator\Type\InterfaceType $interface) : bool
    {
        foreach ($this->implements as $temp) {
            if ($temp->getName() === $interface->getName() || $temp->implements($interface)) {
                return true;
            }
        }

        return false;
    }
}
