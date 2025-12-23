<?php

declare(strict_types = 1);

namespace Graphpinator\Value\Visitor;

use Graphpinator\Typesystem\Contract\TypeVisitor;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Value\Contract\Value;
use Graphpinator\Value\EnumValue;
use Graphpinator\Value\Exception\InvalidValue;
use Graphpinator\Value\Exception\ValueCannotBeNull;
use Graphpinator\Value\ListIntermediateValue;
use Graphpinator\Value\NullValue;
use Graphpinator\Value\ScalarValue;
use Graphpinator\Value\TypeIntermediateValue;

/**
 * @implements TypeVisitor<Value>
 */
final readonly class CreateResolvedValueVisitor implements TypeVisitor
{
    public function __construct(
        private mixed $rawValue,
    )
    {
    }

    #[\Override]
    public function visitType(Type $type) : NullValue|TypeIntermediateValue
    {
        return $this->rawValue === null
            ? new NullValue($type)
            : new TypeIntermediateValue($type, $this->rawValue);
    }

    #[\Override]
    public function visitInterface(InterfaceType $interface) : NullValue|TypeIntermediateValue
    {
        return $this->rawValue === null
            ? new NullValue($interface)
            : $interface->createResolvedValue($this->rawValue);
    }

    #[\Override]
    public function visitUnion(UnionType $union) : NullValue|TypeIntermediateValue
    {
        return $this->rawValue === null
            ? new NullValue($union)
            : $union->createResolvedValue($this->rawValue);
    }

    #[\Override]
    public function visitInput(InputType $input) : never
    {
        throw new \LogicException(); // @codeCoverageIgnore
    }

    #[\Override]
    public function visitScalar(ScalarType $scalar) : NullValue|ScalarValue
    {
        return $this->rawValue === null
            ? new NullValue($scalar)
            : new ScalarValue($scalar, $this->rawValue, false);
    }

    #[\Override]
    public function visitEnum(EnumType $enum) : NullValue|EnumValue
    {
        return $this->rawValue === null
            ? new NullValue($enum)
            : new EnumValue($enum, $this->rawValue, false);
    }

    #[\Override]
    public function visitNotNull(NotNullType $notNull) : Value
    {
        return $this->rawValue === null
            ? throw new ValueCannotBeNull(false)
            : $notNull->getInnerType()->accept($this);
    }

    #[\Override]
    public function visitList(ListType $list) : NullValue|ListIntermediateValue
    {
        if ($this->rawValue === null) {
            return new NullValue($list);
        }

        if (\is_iterable($this->rawValue)) {
            return new ListIntermediateValue($list, $this->rawValue);
        }

        throw new InvalidValue($list, $this->rawValue, false);
    }
}
