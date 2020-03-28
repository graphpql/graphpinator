<?php

declare(strict_types = 1);

namespace PGQL\Type;

abstract class Type extends \PGQL\Type\Contract\ConcreteDefinition implements
    \PGQL\Type\Contract\Resolvable,
    \PGQL\Type\Utils\FieldContainer,
    \PGQL\Type\Utils\InterfaceImplementor
{
    use \PGQL\Type\Contract\TResolvable;
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

    public function isInstanceOf(\PGQL\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof \PGQL\Type\Contract\AbstractDefinition) {
            return $type->isImplementedBy($this);
        }

        return parent::isInstanceOf($type);
    }
}
