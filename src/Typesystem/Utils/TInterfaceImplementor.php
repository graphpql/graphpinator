<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Utils;

use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\InterfaceSet;
use Graphpinator\Typesystem\InterfaceType;

/**
 * Trait TInterfaceImplementor which is implementation of InterfaceImplementor interface.
 */
trait TInterfaceImplementor
{
    protected ?FieldSet $fields = null;
    protected InterfaceSet $implements;

    /**
     * Returns interfaces, which this type implements.
     */
    public function getInterfaces() : InterfaceSet
    {
        return $this->implements;
    }

    /**
     * Checks whether this type implements given interface.
     * @param InterfaceType $interface
     */
    public function implements(InterfaceType $interface) : bool
    {
        foreach ($this->implements as $temp) {
            if ($temp::class === $interface::class || $temp->implements($interface)) {
                return true;
            }
        }

        return false;
    }
}
