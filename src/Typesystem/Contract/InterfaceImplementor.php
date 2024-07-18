<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Contract;

use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\InterfaceSet;
use Graphpinator\Typesystem\InterfaceType;

/**
 * Interface InterfaceImplementor which marks types which can implement interface - currently Type and Interface.
 */
//@phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousInterfaceNaming.SuperfluousPrefix
interface InterfaceImplementor extends Type
{
    /**
     * Returns fields defined for this type.
     */
    public function getFields() : FieldSet;

    /**
     * Returns interfaces, which this type implements.
     */
    public function getInterfaces() : InterfaceSet;

    /**
     * Checks whether this type implements given interface.
     * @param InterfaceType $interface
     */
    public function implements(InterfaceType $interface) : bool;
}
