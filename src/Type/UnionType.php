<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class UnionType extends \Graphpinator\Type\Contract\AbstractDefinition
{
    protected \Graphpinator\Utils\ConcreteSet $types;

    public function __construct(\Graphpinator\Utils\ConcreteSet $types)
    {
        $this->types = $types;
    }

    final public function getTypes() : \Graphpinator\Utils\ConcreteSet
    {
        return $this->types;
    }

    final public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static;
    }

    final public function isImplementedBy(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        foreach ($this->types as $temp) {
            if ($temp->isInstanceOf($type)) {
                return true;
            }
        }

        return false;
    }

    final public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::UNION;
    }

    final public function printSchema() : string
    {
        $typeNames = [];

        foreach ($this->getTypes() as $type) {
            $typeNames[] = $type->printName();
        }

        return 'union ' . $this->getName() . ' = ' . \implode(' | ', $typeNames);
    }
}
