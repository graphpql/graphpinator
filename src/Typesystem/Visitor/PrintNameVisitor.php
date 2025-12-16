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
 * @implements TypeVisitor<string>
 */
final readonly class PrintNameVisitor implements TypeVisitor
{
    #[\Override]
    public function visitType(Type $type) : string
    {
        return $type->getName();
    }

    #[\Override]
    public function visitInterface(InterfaceType $interface) : string
    {
        return $interface->getName();
    }

    #[\Override]
    public function visitUnion(UnionType $union) : string
    {
        return $union->getName();
    }

    #[\Override]
    public function visitInput(InputType $input) : string
    {
        return $input->getName();
    }

    #[\Override]
    public function visitScalar(ScalarType $scalar) : string
    {
        return $scalar->getName();
    }

    #[\Override]
    public function visitEnum(EnumType $enum) : string
    {
        return $enum->getName();
    }

    #[\Override]
    public function visitNotNull(NotNullType $notNull) : string
    {
        return $notNull->getInnerType()->accept($this) . '!';
    }

    #[\Override]
    public function visitList(ListType $list) : string
    {
        return '[' . $list->getInnerType()->accept($this) . ']';
    }
}
