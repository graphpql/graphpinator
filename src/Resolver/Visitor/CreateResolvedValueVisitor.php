<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver\Visitor;

use Graphpinator\Typesystem\Contract\TypeVisitor;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Value\Exception\ValueCannotBeNull;
use Graphpinator\Value\ListIntermediateValue;
use Graphpinator\Value\NullValue;
use Graphpinator\Value\ResolvedValue;
use Graphpinator\Value\ScalarValue;
use Graphpinator\Value\TypeIntermediateValue;

/**
 * @implements TypeVisitor<ResolvedValue>
 */
final class CreateResolvedValueVisitor implements TypeVisitor
{
    public function __construct(
        private mixed $rawValue,
    )
    {
    }

    #[\Override]
    public function visitType(Type $type) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullValue($type);
        }

        return new TypeIntermediateValue($type, $this->rawValue);
    }

    #[\Override]
    public function visitInterface(InterfaceType $interface) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullValue($interface);
        }

        return $interface->createResolvedValue($this->rawValue);
    }

    #[\Override]
    public function visitUnion(UnionType $union) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullValue($union);
        }

        return $union->createResolvedValue($this->rawValue);
    }

    #[\Override]
    public function visitInput(InputType $input) : mixed
    {
        throw new \LogicException();
    }

    #[\Override]
    public function visitScalar(ScalarType $scalar) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullValue($scalar);
        }

        return new ScalarValue($scalar, $this->rawValue, false);
    }

    #[\Override]
    public function visitEnum(EnumType $enum) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullValue($enum);
        }

        if (\is_object($this->rawValue)) {
            $this->rawValue = $this->rawValue->value;
        }

        return new ScalarValue($enum, $this->rawValue, false);
    }

    #[\Override]
    public function visitNotNull(NotNullType $notNull) : ResolvedValue
    {
        $value = $notNull->getInnerType()->accept($this);

        if ($value instanceof NullValue) {
            throw new ValueCannotBeNull(false);
        }

        return $value;
    }

    #[\Override]
    public function visitList(ListType $list) : ResolvedValue
    {
        if (\is_iterable($this->rawValue)) {
            return new ListIntermediateValue($list, $this->rawValue);
        }

        return new NullValue($list);
    }
}
