<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

/**
 * Interface InterfaceImplementor which marks types which can implement interface - currently Type and Interface.
 */
interface InterfaceImplementor
{
    /**
     * Returns fields defined for this type.
     */
    public function getFields() : \Graphpinator\Field\FieldSet;

    /**
     * Returns interfaces, which this type implements.
     */
    public function getInterfaces() : \Graphpinator\Utils\InterfaceSet;

    /**
     * Checks whether this type implements given interface.
     */
    public function implements(\Graphpinator\Type\InterfaceType $interface) : bool;
}
