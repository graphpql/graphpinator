<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Visitor;

use Graphpinator\Typesystem\Contract\AbstractType;
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
 * Compares whether visited type is instanceof typeToCompare, including variance.
 * - Int! is instanceof Int!
 * - Int is instanceof Int
 * - Int! is instanceof Int -> as it is a subset
 * - Int is NOT instanceof Int!
 * - UnionType is NOT instanceof UnionTypeItem
 *
 * @implements TypeVisitor<bool>
 */
final readonly class IsInstanceOfVisitor implements TypeVisitor
{
    public function __construct(
        private TypeContract $typeToCompare,
    )
    {
    }

    #[\Override]
    public function visitType(Type $type) : bool
    {
        return $type::class === $this->typeToCompare::class
            || ($this->typeToCompare instanceof AbstractType && $this->typeToCompare->accept(new IsImplementedByVisitor($type)));
    }

    #[\Override]
    public function visitInterface(InterfaceType $interface) : bool
    {
        return $interface::class === $this->typeToCompare::class
            || ($this->typeToCompare instanceof AbstractType && $this->typeToCompare->accept(new IsImplementedByVisitor($interface)));
    }

    #[\Override]
    public function visitUnion(UnionType $union) : bool
    {
        return $union::class === $this->typeToCompare::class;
    }

    #[\Override]
    public function visitInput(InputType $input) : bool
    {
        return $input::class === $this->typeToCompare::class;
    }

    #[\Override]
    public function visitScalar(ScalarType $scalar) : bool
    {
        return $scalar::class === $this->typeToCompare::class;
    }

    #[\Override]
    public function visitEnum(EnumType $enum) : bool
    {
        return $enum::class === $this->typeToCompare::class;
    }

    #[\Override]
    public function visitNotNull(NotNullType $notNull) : bool
    {
        return $this->typeToCompare instanceof NotNullType
            ? $notNull->getInnerType()->accept(new self($this->typeToCompare->getInnerType()))
            : $notNull->getInnerType()->accept($this);
    }

    #[\Override]
    public function visitList(ListType $list) : bool
    {
        return $this->typeToCompare instanceof ListType
            ? $list->getInnerType()->accept(new self($this->typeToCompare->getInnerType()))
            : false;
    }
}
