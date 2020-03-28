<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type;

abstract class Type extends \Infinityloop\Graphpinator\Type\Contract\ConcreteDefinition implements
    \Infinityloop\Graphpinator\Type\Contract\Resolvable,
    \Infinityloop\Graphpinator\Type\Utils\FieldContainer,
    \Infinityloop\Graphpinator\Type\Utils\InterfaceImplementor
{
    use \Infinityloop\Graphpinator\Type\Contract\TResolvable;
    use \Infinityloop\Graphpinator\Type\Utils\TFieldContainer;
    use \Infinityloop\Graphpinator\Type\Utils\TInterfaceImplementor;

    public function __construct(\Infinityloop\Graphpinator\Field\FieldSet $fields, ?\Infinityloop\Graphpinator\Type\Utils\InterfaceSet $implements = null)
    {
        $this->fields = $fields;
        $this->implements = $implements instanceof \Infinityloop\Graphpinator\Type\Utils\InterfaceSet
            ? $implements
            : new \Infinityloop\Graphpinator\Type\Utils\InterfaceSet([]);
    }

    public function createValue($rawValue) : \Infinityloop\Graphpinator\Value\ValidatedValue
    {
        return \Infinityloop\Graphpinator\Value\TypeValue::create($rawValue, $this);
    }

    public function isInstanceOf(\Infinityloop\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof \Infinityloop\Graphpinator\Type\Contract\AbstractDefinition) {
            return $type->isImplementedBy($this);
        }

        return parent::isInstanceOf($type);
    }
}
