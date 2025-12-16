<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Visitor;

use Graphpinator\Typesystem\Contract\InterfaceImplementor;
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
 * @implements TypeVisitor<bool>
 */
final readonly class IsImplementedByVisitor implements TypeVisitor
{
    private TypeContract $typeToCompare;

    public function __construct(
        TypeContract $typeToCompare,
    )
    {
        $this->typeToCompare = $typeToCompare->accept(new GetShapingTypeVisitor());
    }

    #[\Override]
    public function visitType(Type $type) : bool
    {
        return $type::class === $this->typeToCompare::class;
    }

    #[\Override]
    public function visitInterface(InterfaceType $interface) : bool
    {
        return $interface::class === $this->typeToCompare::class
            || ($this->typeToCompare instanceof InterfaceImplementor && $this->typeToCompare->implements($interface));
    }

    #[\Override]
    public function visitUnion(UnionType $union) : bool
    {
        return $union::class === $this->typeToCompare::class
            || $this->isOneOfUnionTypes($union);
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
        return $notNull->getInnerType()->accept($this);
    }

    #[\Override]
    public function visitList(ListType $list) : bool
    {
        return $this->typeToCompare instanceof ListType
            ? $list->getInnerType()->accept(new self($this->typeToCompare->getInnerType()))
            : false;
    }

    private function isOneOfUnionTypes(UnionType $union) : bool
    {
        foreach ($union->getTypes() as $unionItem) {
            if ($unionItem->accept(new IsInstanceOfVisitor($this->typeToCompare))) {
                return true;
            }
        }

        return false;
    }
}
