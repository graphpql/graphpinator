<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class CreateResolvedValueVisitor implements \Graphpinator\Typesystem\TypeVisitor
{
    public function __construct(
        private mixed $rawValue,
    ) {}

    public function visitType(\Graphpinator\Type\Type $type) : \Graphpinator\Value\ResolvedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($type);
        }

        return new \Graphpinator\Value\TypeIntermediateValue($type, $this->rawValue);
    }

    public function visitInterface(\Graphpinator\Type\InterfaceType $interface) : \Graphpinator\Value\ResolvedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($interface);
        }

        return $interface->createResolvedValue($this->rawValue);
    }

    public function visitUnion(\Graphpinator\Type\UnionType $union) : \Graphpinator\Value\ResolvedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($union);
        }

        return $union->createResolvedValue($this->rawValue);
    }

    public function visitInput(\Graphpinator\Type\InputType $input) : mixed
    {
        // nothing here
    }

    public function visitScalar(\Graphpinator\Type\Scalar\ScalarType $scalar) : \Graphpinator\Value\ResolvedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($scalar);
        }

        return new \Graphpinator\Value\ScalarValue($scalar, $this->rawValue, false);
    }

    public function visitEnum(\Graphpinator\Type\EnumType $enum) : \Graphpinator\Value\ResolvedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($enum);
        }

        return new \Graphpinator\Value\ScalarValue($enum, $this->rawValue, false);
    }

    public function visitNotNull(\Graphpinator\Type\NotNullType $notNull) : \Graphpinator\Value\ResolvedValue
    {
        $value = $notNull->getInnerType()->accept($this);

        if ($value instanceof \Graphpinator\Value\NullValue) {
            throw new \Graphpinator\Exception\Value\ValueCannotBeNull(false);
        }

        return $value;
    }

    public function visitList(\Graphpinator\Type\ListType $list) : \Graphpinator\Value\ResolvedValue
    {
        if (\is_iterable($this->rawValue)) {
            return new \Graphpinator\Value\ListIntermediateValue($list, $this->rawValue);
        }

        return new \Graphpinator\Value\NullResolvedValue($list);
    }
}
