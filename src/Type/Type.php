<?php

declare(strict_types = 1);

namespace PGQL\Type;

abstract class Type extends ConcreteDefinition implements
    Outputable,
    \PGQL\Type\Utils\FieldContainer,
    \PGQL\Type\Utils\InterfaceImplementor
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
        if ($type instanceof AbstractDefinition) {
            return $type->isImplementedBy($this);
        }

        return parent::isInstanceOf($type);
    }
}
