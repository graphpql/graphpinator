<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use Graphpinator\Typesystem\Contract\TypeVisitor;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\UnionType;

final class TypeKindVisitor implements TypeVisitor
{
    public function visitType(Type $type) : string
    {
        return TypeKind::OBJECT;
    }

    public function visitInterface(InterfaceType $interface) : string
    {
        return TypeKind::INTERFACE;
    }

    public function visitUnion(UnionType $union) : string
    {
        return TypeKind::UNION;
    }

    public function visitInput(InputType $input) : string
    {
        return TypeKind::INPUT_OBJECT;
    }

    public function visitScalar(ScalarType $scalar) : string
    {
        return TypeKind::SCALAR;
    }

    public function visitEnum(EnumType $enum) : string
    {
        return TypeKind::ENUM;
    }

    public function visitNotNull(NotNullType $notNull) : string
    {
        return TypeKind::NON_NULL;
    }

    public function visitList(ListType $list) : string
    {
        return TypeKind::LIST;
    }
}
