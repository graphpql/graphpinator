<?php

declare(strict_types = 1);

namespace PGQL\Type;

use PGQL\Type\Contract\AbstractDefinition;
use PGQL\Type\Contract\ConcreteDefinition;
use PGQL\Type\Contract\Definition;
use PGQL\Type\Contract\Outputable;
use PGQL\Type\Contract\Resolvable;

abstract class Type extends ConcreteDefinition implements
    Outputable,
    Resolvable,
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

    public function createValue($rawValue) : \PGQL\Value\ValidatedValue
    {
        return \PGQL\Value\TypeValue::create($rawValue, $this);
    }

    public function isInstanceOf(Definition $type) : bool
    {
        if ($type instanceof AbstractDefinition) {
            return $type->isImplementedBy($this);
        }

        return parent::isInstanceOf($type);
    }
}
