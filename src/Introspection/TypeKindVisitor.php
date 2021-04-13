<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

final class TypeKindVisitor implements \Graphpinator\Typesystem\TypeVisitor
{
    use \Nette\SmartObject;

    public function visitType(\Graphpinator\Type\Type $type) : string
    {
        return TypeKind::OBJECT;
    }

    public function visitInterface(\Graphpinator\Type\InterfaceType $interface) : string
    {
        return TypeKind::INTERFACE;
    }

    public function visitUnion(\Graphpinator\Type\UnionType $union) : string
    {
        return TypeKind::UNION;
    }

    public function visitInput(\Graphpinator\Type\InputType $input) : string
    {
        return TypeKind::INPUT_OBJECT;
    }

    public function visitScalar(\Graphpinator\Type\ScalarType $scalar) : string
    {
        return TypeKind::SCALAR;
    }

    public function visitEnum(\Graphpinator\Type\EnumType $enum) : string
    {
        return TypeKind::ENUM;
    }

    public function visitNotNull(\Graphpinator\Type\NotNullType $notNull) : string
    {
        return TypeKind::NON_NULL;
    }

    public function visitList(\Graphpinator\Type\ListType $list) : string
    {
        return TypeKind::LIST;
    }
}
