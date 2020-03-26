<?php

declare(strict_types = 1);

namespace PGQL\Type\Utils;

trait TInterfaceImplementor
{
    protected \PGQL\Type\Utils\InterfaceSet $implements;

    public function getInterfaces() : InterfaceSet
    {
        return $this->implements;
    }

   public function implements(\PGQL\Type\InterfaceType $interface) : bool
    {
        foreach ($this->implements as $temp) {
            if ($temp->getName() === $interface->getName() || $temp->implements($interface)) {
                return true;
            }
        }

        return false;
    }
}
