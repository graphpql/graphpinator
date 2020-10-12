<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class InterfaceType extends \Graphpinator\Type\Contract\AbstractDefinition implements
    \Graphpinator\Type\Contract\InterfaceImplementor
{
    use \Graphpinator\Type\Contract\TInterfaceImplementor;
    use \Graphpinator\Printable\TRepeatablePrint;

    public function __construct(?\Graphpinator\Utils\InterfaceSet $implements = null)
    {
        $this->implements = $implements
            ?? new \Graphpinator\Utils\InterfaceSet([]);
    }

    final public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static
            || ($type instanceof self && $this->implements($type));
    }

    final public function isImplementedBy(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isImplementedBy($type->getInnerType());
        }

        return $type instanceof \Graphpinator\Type\Contract\InterfaceImplementor
            && $type->implements($this);
    }

    final public function getFields() : \Graphpinator\Field\FieldSet
    {
        if (!$this->fields instanceof \Graphpinator\Field\FieldSet) {
            $this->fields = $this->getFieldDefinition();

            $this->validateInterfaces();
        }

        return $this->fields;
    }

    final public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::INTERFACE;
    }

    final public function printSchema() : string
    {
        return $this->printDescription()
            . 'interface ' . $this->getName() . $this->printImplements() . ' {' . \PHP_EOL
            . $this->printItems($this->getFields(), 1)
            . '}';
    }

    abstract protected function getFieldDefinition() : \Graphpinator\Field\FieldSet;
}
