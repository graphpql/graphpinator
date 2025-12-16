<?php

declare(strict_types = 1);

namespace GraphQL\Typesystem\Visitor;

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
 * @implements TypeVisitor<bool>
 */
final readonly class IsInputableVisitor implements TypeVisitor
{
    #[\Override]
    public function visitType(Type $type) : false
    {
        return false;
    }

    #[\Override]
    public function visitInterface(InterfaceType $interface) : false
    {
        return false;
    }

    #[\Override]
    public function visitUnion(UnionType $union) : false
    {
        return false;
    }

    #[\Override]
    public function visitInput(InputType $input) : true
    {
        return true;
    }

    #[\Override]
    public function visitScalar(ScalarType $scalar) : true
    {
        return true;
    }

    #[\Override]
    public function visitEnum(EnumType $enum) : true
    {
        return true;
    }

    #[\Override]
    public function visitNotNull(NotNullType $notNull) : bool
    {
        return $notNull->getInnerType()->accept($this);
    }

    #[\Override]
    public function visitList(ListType $list) : bool
    {
        return $list->getInnerType()->accept($this);
    }
}
