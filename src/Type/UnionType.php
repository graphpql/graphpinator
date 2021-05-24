<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class UnionType extends \Graphpinator\Type\Contract\AbstractDefinition
{
    use \Graphpinator\Type\Contract\TMetaFields;

    public function __construct(protected \Graphpinator\Type\TypeSet $types)
    {
    }

    final public function getTypes() : \Graphpinator\Type\TypeSet
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

    final public function accept(\Graphpinator\Typesystem\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitUnion($this);
    }
}
