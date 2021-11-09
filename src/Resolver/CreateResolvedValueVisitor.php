<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

use \Graphpinator\Exception\Value\ValueCannotBeNull;
use \Graphpinator\Value\NullResolvedValue;
use \Graphpinator\Value\ResolvedValue;

final class CreateResolvedValueVisitor implements \Graphpinator\Typesystem\Contract\TypeVisitor
{
    public function __construct(
        private mixed $rawValue,
    )
    {
    }

    public function visitType(\Graphpinator\Typesystem\Type $type) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullResolvedValue($type);
        }

        return new \Graphpinator\Value\TypeIntermediateValue($type, $this->rawValue);
    }

    public function visitInterface(\Graphpinator\Typesystem\InterfaceType $interface) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullResolvedValue($interface);
        }

        return $interface->createResolvedValue($this->rawValue);
    }

    public function visitUnion(\Graphpinator\Typesystem\UnionType $union) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullResolvedValue($union);
        }

        return $union->createResolvedValue($this->rawValue);
    }

    public function visitInput(\Graphpinator\Typesystem\InputType $input) : mixed
    {
        // nothing here
    }

    public function visitScalar(\Graphpinator\Typesystem\ScalarType $scalar) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullResolvedValue($scalar);
        }

        return new \Graphpinator\Value\ScalarValue($scalar, $this->rawValue, false);
    }

    public function visitEnum(\Graphpinator\Typesystem\EnumType $enum) : ResolvedValue
    {
        if ($this->rawValue === null) {
            return new NullResolvedValue($enum);
        }

        return new \Graphpinator\Value\ScalarValue($enum, $this->rawValue, false);
    }

    public function visitNotNull(\Graphpinator\Typesystem\NotNullType $notNull) : ResolvedValue
    {
        $value = $notNull->getInnerType()->accept($this);

        if ($value instanceof \Graphpinator\Value\NullValue) {
            throw new ValueCannotBeNull(false);
        }

        return $value;
    }

    public function visitList(\Graphpinator\Typesystem\ListType $list) : ResolvedValue
    {
        if (\is_iterable($this->rawValue)) {
            return new \Graphpinator\Value\ListIntermediateValue($list, $this->rawValue);
        }

        return new NullResolvedValue($list);
    }
}
