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

/**
 * @implements TypeVisitor<string>
 */
final class TypeKindVisitor implements TypeVisitor
{
    #[\Override]
    public function visitType(Type $type) : string
    {
        return TypeKind::OBJECT;
    }

    #[\Override]
    public function visitInterface(InterfaceType $interface) : string
    {
        return TypeKind::INTERFACE;
    }

    #[\Override]
    public function visitUnion(UnionType $union) : string
    {
        return TypeKind::UNION;
    }

    #[\Override]
    public function visitInput(InputType $input) : string
    {
        return TypeKind::INPUT_OBJECT;
    }

    #[\Override]
    public function visitScalar(ScalarType $scalar) : string
    {
        return TypeKind::SCALAR;
    }

    #[\Override]
    public function visitEnum(EnumType $enum) : string
    {
        return TypeKind::ENUM;
    }

    #[\Override]
    public function visitNotNull(NotNullType $notNull) : string
    {
        return TypeKind::NON_NULL;
    }

    #[\Override]
    public function visitList(ListType $list) : string
    {
        return TypeKind::LIST;
    }
}
