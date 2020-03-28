<?php

declare(strict_types = 1);

namespace PGQL\Type;

use PGQL\Type\Contract\AbstractDefinition;
use PGQL\Type\Contract\Definition;
use PGQL\Type\Contract\Outputable;

abstract class InterfaceType extends AbstractDefinition implements Outputable, \PGQL\Type\Utils\FieldContainer, \PGQL\Type\Utils\InterfaceImplementor
{
    use \PGQL\Type\Utils\TFieldContainer;
    use \PGQL\Type\Utils\TInterfaceImplementor;

    public function __construct(\PGQL\Field\FieldSet $fields, ?\PGQL\Type\Utils\InterfaceSet $implements = null)
    {
        $this->fields = $fields;
        $this->implements = $implements instanceof \PGQL\Type\Utils\InterfaceSet
            ? $implements
            : new \PGQL\Type\Utils\InterfaceSet([]);
    }

    public function isInstanceOf(Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static
            || ($type instanceof self && $this->implements($type));
    }

    public function isImplementedBy(Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isImplementedBy($type->getInnerType());
        }

        return $type instanceof \PGQL\Type\Utils\InterfaceImplementor
            && $type->implements($this);
    }
}
