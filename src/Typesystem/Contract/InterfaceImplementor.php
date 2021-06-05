<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

/**
 * Interface InterfaceImplementor which marks types which can implement interface - currently Type and Interface.
 */
//@phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousInterfaceNaming.SuperfluousPrefix
interface InterfaceImplementor extends \Graphpinator\Typesystem\Contract\Type
{
    /**
     * Returns fields defined for this type.
     */
    public function getFields() : \Graphpinator\Typesystem\Field\FieldSet;

    /**
     * Returns interfaces, which this type implements.
     */
    public function getInterfaces() : \Graphpinator\Typesystem\InterfaceSet;

    /**
     * Checks whether this type implements given interface.
     * @param \Graphpinator\Typesystem\InterfaceType $interface
     */
    public function implements(\Graphpinator\Typesystem\InterfaceType $interface) : bool;
}
