<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

final class TypeKindVisitor implements \Graphpinator\Typesystem\Contract\TypeVisitor
{
    public function visitType(\Graphpinator\Typesystem\Type $type) : string
    {
        return TypeKind::OBJECT;
    }

    public function visitInterface(\Graphpinator\Typesystem\InterfaceType $interface) : string
    {
        return TypeKind::INTERFACE;
    }

    public function visitUnion(\Graphpinator\Typesystem\UnionType $union) : string
    {
        return TypeKind::UNION;
    }

    public function visitInput(\Graphpinator\Typesystem\InputType $input) : string
    {
        return TypeKind::INPUT_OBJECT;
    }

    public function visitScalar(\Graphpinator\Typesystem\ScalarType $scalar) : string
    {
        return TypeKind::SCALAR;
    }

    public function visitEnum(\Graphpinator\Typesystem\EnumType $enum) : string
    {
        return TypeKind::ENUM;
    }

    public function visitNotNull(\Graphpinator\Typesystem\NotNullType $notNull) : string
    {
        return TypeKind::NON_NULL;
    }

    public function visitList(\Graphpinator\Typesystem\ListType $list) : string
    {
        return TypeKind::LIST;
    }
}
