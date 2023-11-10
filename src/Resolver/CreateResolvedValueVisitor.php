<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class CreateResolvedValueVisitor implements \Graphpinator\Typesystem\Contract\TypeVisitor
{
    public function __construct(
        private mixed $rawValue,
    )
    {
    }

    public function visitType(\Graphpinator\Typesystem\Type $type) : \Graphpinator\Value\ResolvedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($type);
        }

        return new \Graphpinator\Value\TypeIntermediateValue($type, $this->rawValue);
    }

    public function visitInterface(\Graphpinator\Typesystem\InterfaceType $interface) : \Graphpinator\Value\ResolvedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($interface);
        }

        return $interface->createResolvedValue($this->rawValue);
    }

    public function visitUnion(\Graphpinator\Typesystem\UnionType $union) : \Graphpinator\Value\ResolvedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($union);
        }

        return $union->createResolvedValue($this->rawValue);
    }

    public function visitInput(\Graphpinator\Typesystem\InputType $input) : mixed
    {
        throw new \LogicException();
    }

    public function visitScalar(\Graphpinator\Typesystem\ScalarType $scalar) : \Graphpinator\Value\ResolvedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($scalar);
        }

        return new \Graphpinator\Value\ScalarValue($scalar, $this->rawValue, false);
    }

    public function visitEnum(\Graphpinator\Typesystem\EnumType $enum) : \Graphpinator\Value\ResolvedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullResolvedValue($enum);
        }

        if (\is_object($this->rawValue)) {
            $this->rawValue = $this->rawValue->value;
        }

        return new \Graphpinator\Value\ScalarValue($enum, $this->rawValue, false);
    }

    public function visitNotNull(\Graphpinator\Typesystem\NotNullType $notNull) : \Graphpinator\Value\ResolvedValue
    {
        $value = $notNull->getInnerType()->accept($this);

        if ($value instanceof \Graphpinator\Value\NullValue) {
            throw new \Graphpinator\Exception\Value\ValueCannotBeNull(false);
        }

        return $value;
    }

    public function visitList(\Graphpinator\Typesystem\ListType $list) : \Graphpinator\Value\ResolvedValue
    {
        if (\is_iterable($this->rawValue)) {
            return new \Graphpinator\Value\ListIntermediateValue($list, $this->rawValue);
        }

        return new \Graphpinator\Value\NullResolvedValue($list);
    }
}
