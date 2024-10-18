<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Common\Path;
use Graphpinator\Exception\Value\InvalidValue;
use Graphpinator\Exception\Value\ValueCannotBeNull;
use Graphpinator\Exception\Value\ValueCannotBeOmitted;
use Graphpinator\Normalizer\Exception\UnknownArgument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Contract\Inputable;
use Graphpinator\Typesystem\Contract\TypeVisitor;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\UnionType;

final class ConvertRawValueVisitor implements TypeVisitor
{
    public function __construct(
        readonly private mixed $rawValue,
        readonly private Path $path,
    )
    {
    }

    public static function convertArgumentSet(
        ArgumentSet $arguments,
        \stdClass $rawValue,
        Path $path,
        bool $canBeOmitted = false,
    ) : \stdClass
    {
        $rawValue = self::mergeRaw($rawValue, (object) $arguments->getRawDefaults());

        foreach ((array) $rawValue as $name => $temp) {
            if ($arguments->offsetExists($name)) {
                continue;
            }

            throw new UnknownArgument((string) $name);
        }

        $inner = new \stdClass();

        foreach ($arguments as $argument) {
            $path->add($argument->getName() . ' <argument>');

            if (\property_exists($rawValue, $argument->getName())) {
                $inner->{$argument->getName()} = new ArgumentValue(
                    $argument,
                    $argument->getType()->accept(new self($rawValue->{$argument->getName()}, $path)),
                    false,
                );
                $path->pop();

                continue;
            }

            $default = $argument->getDefaultValue();

            if ($default instanceof ArgumentValue) {
                $inner->{$argument->getName()} = $default;
            } elseif (!$canBeOmitted) {
                $inner->{$argument->getName()} = new ArgumentValue(
                    $argument,
                    $argument->getType()->accept(new self(null, $path)),
                    false,
                );
            } elseif ($argument->getType() instanceof NotNullType) {
                throw new ValueCannotBeOmitted();
            }

            $path->pop();
        }

        return $inner;
    }

    public function visitType(Type $type) : mixed
    {
        throw new \LogicException();
    }

    public function visitInterface(InterfaceType $interface) : mixed
    {
        throw new \LogicException();
    }

    public function visitUnion(UnionType $union) : mixed
    {
        throw new \LogicException();
    }

    public function visitInput(InputType $input) : InputedValue
    {
        if ($this->rawValue === null) {
            return new NullInputedValue($input);
        }

        if (!$this->rawValue instanceof \stdClass) {
            throw new InvalidValue($input->getName(), $this->rawValue, true);
        }

        return new InputValue($input, self::convertArgumentSet($input->getArguments(), $this->rawValue, $this->path, true));
    }

    public function visitScalar(ScalarType $scalar) : InputedValue
    {
        if ($this->rawValue === null) {
            return new NullInputedValue($scalar);
        }

        $coercedValue = $scalar->coerceValue($this->rawValue);

        return new ScalarValue($scalar, $coercedValue, true);
    }

    public function visitEnum(EnumType $enum) : InputedValue
    {
        if ($this->rawValue === null) {
            return new NullInputedValue($enum);
        }

        if ($this->rawValue instanceof \BackedEnum && \is_string($enum->getEnumClass())) {
            return new EnumValue($enum, $this->rawValue->value, true);
        }

        return new EnumValue($enum, $this->rawValue, true);
    }

    public function visitNotNull(NotNullType $notNull) : InputedValue
    {
        $value = $notNull->getInnerType()->accept($this);

        if ($value instanceof NullValue) {
            throw new ValueCannotBeNull(true);
        }

        return $value;
    }

    public function visitList(ListType $list) : InputedValue
    {
        if ($this->rawValue === null) {
            return new NullInputedValue($list);
        }

        $coercedValue = \is_array($this->rawValue)
            ? $this->rawValue
            : [$this->rawValue];

        $innerType = $list->getInnerType();
        \assert($innerType instanceof Inputable);

        $inner = [];

        foreach ($coercedValue as $index => $rawValue) {
            $this->path->add($index . ' <list index>');
            $inner[] = $innerType->accept(new self($rawValue, $this->path));
            $this->path->pop();
        }

        return new ListInputedValue($list, $inner);
    }

    private static function mergeRaw(\stdClass $core, \stdClass $supplement) : \stdClass
    {
        foreach ((array) $supplement as $key => $value) {
            if (\property_exists($core, $key)) {
                if ($core->{$key} instanceof \stdClass &&
                    $supplement->{$key} instanceof \stdClass) {
                    $core->{$key} = self::mergeRaw($core->{$key}, $supplement->{$key});
                }

                continue;
            }

            $core->{$key} = $value;
        }

        return $core;
    }
}
