<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class UnionType extends \Graphpinator\Type\Contract\AbstractDefinition implements \Graphpinator\Type\Contract\Outputable
{
    protected \Graphpinator\Utils\ConcreteSet $types;

    public function __construct(\Graphpinator\Utils\ConcreteSet $types)
    {
        $this->types = $types;
    }

    public function getTypes() : \Graphpinator\Utils\ConcreteSet
    {
        return $this->types;
    }

    public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static;
    }

    public function isImplementedBy(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        foreach ($this->types as $temp) {
            if ($temp->isInstanceOf($type)) {
                return true;
            }
        }

        return false;
    }

    public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::UNION;
    }
}
