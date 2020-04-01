<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class Type extends \Graphpinator\Type\Contract\ConcreteDefinition implements
    \Graphpinator\Type\Contract\Resolvable,
    \Graphpinator\Type\Utils\FieldContainer,
    \Graphpinator\Type\Utils\InterfaceImplementor
{
    use \Graphpinator\Type\Contract\TResolvable;
    use \Graphpinator\Type\Utils\TFieldContainer;
    use \Graphpinator\Type\Utils\TInterfaceImplementor;

    public function __construct(\Graphpinator\Field\FieldSet $fields, ?\Graphpinator\Type\Utils\InterfaceSet $implements = null)
    {
        $this->fields = $fields;
        $this->implements = $implements instanceof \Graphpinator\Type\Utils\InterfaceSet
            ? $implements
            : new \Graphpinator\Type\Utils\InterfaceSet([]);
    }

    public function createValue($rawValue) : \Graphpinator\Value\ValidatedValue
    {
        return \Graphpinator\Value\TypeValue::create($rawValue, $this);
    }

    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof \Graphpinator\Type\Contract\AbstractDefinition) {
            return $type->isImplementedBy($this);
        }

        return parent::isInstanceOf($type);
    }
}
