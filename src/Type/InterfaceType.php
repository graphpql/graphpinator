<?php

declare(strict_types = 1);

namespace PGQL\Type;

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
        if ($type instanceof NotNull) {
            return $this->isInstanceOf($type->getInnerType());
        }

        if ($type instanceof self) {
            if ($this->getName() === $type->getName()) {
                return true;
            }

            return $this->implements($type);
        }

        return false;
    }

    public function isImplementedBy(Definition $type) : bool
    {
        if ($type instanceof NotNull) {
            return $this->isImplementedBy($type->getInnerType());
        }

        if (!$type instanceof \PGQL\Type\Utils\InterfaceImplementor) {
            return false;
        }

        return $type->implements($this);
    }
}
