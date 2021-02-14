<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class UnionType extends \Graphpinator\Type\Contract\AbstractDefinition implements
    \Graphpinator\Typesystem\Entity
{
    use \Graphpinator\Type\Contract\TMetaFields;

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

    final public function getField(string $name) : \Graphpinator\Field\Field
    {
        return $this->getMetaFields()[$name]
            ?? throw new \Graphpinator\Exception\Normalizer\SelectionOnUnion();
    }

    final public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::UNION;
    }

    final public function accept(\Graphpinator\Typesystem\EntityVisitor $visitor) : mixed
    {
        return $visitor->visitUnion($this);
    }
}
