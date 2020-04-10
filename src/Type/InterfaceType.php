<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class InterfaceType extends \Graphpinator\Type\Contract\AbstractDefinition implements
    \Graphpinator\Type\Contract\Outputable,
    \Graphpinator\Type\Utils\InterfaceImplementor
{
    use \Graphpinator\Type\Utils\TInterfaceImplementor;

    public function __construct(?\Graphpinator\Type\Utils\InterfaceSet $implements = null)
    {
        $this->implements = $implements ?? new \Graphpinator\Type\Utils\InterfaceSet([]);
    }

    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static
            || ($type instanceof self && $this->implements($type));
    }

    public function isImplementedBy(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isImplementedBy($type->getInnerType());
        }

        return $type instanceof \Graphpinator\Type\Utils\InterfaceImplementor
            && $type->implements($this);
    }

    public function getFields() : \Graphpinator\Field\FieldSet
    {
        if (!$this->fields instanceof \Graphpinator\Field\FieldSet) {
            $this->fields = $this->getFieldDefinition();

            $this->validateInterfaces();
        }

        return $this->fields;
    }

    public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::INTERFACE;
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Field\FieldSet;
}
