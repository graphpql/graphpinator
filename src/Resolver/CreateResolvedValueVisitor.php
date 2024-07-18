<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

use Graphpinator\Exception\Value\ValueCannotBeNull;
use Graphpinator\Typesystem\Contract\TypeVisitor;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Value\ListIntermediateValue;
use Graphpinator\Value\NullResolvedValue;
use Graphpinator\Value\NullValue;
use Graphpinator\Value\ResolvedValue;
use Graphpinator\Value\ScalarValue;
use Graphpinator\Value\TypeIntermediateValue;

final class CreateResolvedValueVisitor implements TypeVisitor
{
    public function __construct(
        private mixed $rawValue,
    )
    {
    }

    public function visitType(Type $type) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullResolvedValue($type);
        }

        return new TypeIntermediateValue($type, $this->rawValue);
    }

    public function visitInterface(InterfaceType $interface) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullResolvedValue($interface);
        }

        return $interface->createResolvedValue($this->rawValue);
    }

    public function visitUnion(UnionType $union) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullResolvedValue($union);
        }

        return $union->createResolvedValue($this->rawValue);
    }

    public function visitInput(InputType $input) : mixed
    {
        throw new \LogicException();
    }

    public function visitScalar(ScalarType $scalar) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullResolvedValue($scalar);
        }

        return new ScalarValue($scalar, $this->rawValue, false);
    }

    public function visitEnum(EnumType $enum) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullResolvedValue($enum);
        }

        if (\is_object($this->rawValue)) {
            $this->rawValue = $this->rawValue->value;
        }

        return new ScalarValue($enum, $this->rawValue, false);
    }

    public function visitNotNull(NotNullType $notNull) : ResolvedValue
    {
        $value = $notNull->getInnerType()->accept($this);

        if ($value instanceof NullValue) {
            throw new ValueCannotBeNull(false);
        }

        return $value;
    }

    public function visitList(ListType $list) : ResolvedValue
    {
        if (\is_iterable($this->rawValue)) {
            return new ListIntermediateValue($list, $this->rawValue);
        }

        return new NullResolvedValue($list);
    }
}
