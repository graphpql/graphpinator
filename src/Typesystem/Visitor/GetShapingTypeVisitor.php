<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Visitor;

use Graphpinator\Typesystem\Contract\Type as TypeContract;
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
 * @implements TypeVisitor<TypeContract>
 */
final readonly class GetShapingTypeVisitor implements TypeVisitor
{
    #[\Override]
    public function visitType(Type $type) : Type
    {
        return $type;
    }

    #[\Override]
    public function visitInterface(InterfaceType $interface) : InterfaceType
    {
        return $interface;
    }

    #[\Override]
    public function visitUnion(UnionType $union) : UnionType
    {
        return $union;
    }

    #[\Override]
    public function visitInput(InputType $input) : InputType
    {
        return $input;
    }

    #[\Override]
    public function visitScalar(ScalarType $scalar) : ScalarType
    {
        return $scalar;
    }

    #[\Override]
    public function visitEnum(EnumType $enum) : EnumType
    {
        return $enum;
    }

    #[\Override]
    public function visitNotNull(NotNullType $notNull) : TypeContract
    {
        return $notNull->getInnerType()->accept($this);
    }

    #[\Override]
    public function visitList(ListType $list) : ListType
    {
        return $list;
    }
}
