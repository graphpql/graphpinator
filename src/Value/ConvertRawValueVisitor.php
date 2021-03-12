<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ConvertRawValueVisitor implements \Graphpinator\Typesystem\TypeVisitor
{
    use \Nette\SmartObject;

    public function __construct(
        private mixed $rawValue,
        private ?\Graphpinator\Common\Path $path = null,
    ) {}

    public function visitType(\Graphpinator\Type\Type $type) : mixed
    {
        // nothing here
    }

    public function visitInterface(\Graphpinator\Type\InterfaceType $interface) : mixed
    {
        // nothing here
    }

    public function visitUnion(\Graphpinator\Type\UnionType $union) : mixed
    {
        // nothing here
    }

    public function visitInput(\Graphpinator\Type\InputType $input) : InputedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($input);
        }

        if (!$this->rawValue instanceof \stdClass) {
            throw new \Graphpinator\Exception\Value\InvalidValue($input->getName(), $this->rawValue, true);
        }

        return new InputValue($input, self::convertArgumentSet($input->getArguments(), $this->rawValue));
    }

    public function visitScalar(\Graphpinator\Type\ScalarType $scalar) : InputedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($scalar);
        }

        $this->rawValue = $scalar->coerceValue($this->rawValue);

        return new \Graphpinator\Value\ScalarValue($scalar, $this->rawValue, true);
    }

    public function visitEnum(\Graphpinator\Type\EnumType $enum) : InputedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($enum);
        }

        return new \Graphpinator\Value\EnumValue($enum, $this->rawValue, true);
    }

    public function visitNotNull(\Graphpinator\Type\NotNullType $notNull) : InputedValue
    {
        $value = $notNull->getInnerType()->accept($this);

        if ($value instanceof \Graphpinator\Value\NullValue) {
            throw new \Graphpinator\Exception\Value\ValueCannotBeNull(true);
        }

        return $value;
    }

    public function visitList(\Graphpinator\Type\ListType $list) : InputedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($list);
        }

        if (!\is_array($this->rawValue)) {
            throw new \Graphpinator\Exception\Value\InvalidValue($list->printName(), $this->rawValue, true);
        }

        $innerType = $list->getInnerType();
        \assert($innerType instanceof \Graphpinator\Type\Contract\Inputable);

        $inner = [];
        $listValue = $this->rawValue;

        foreach ($listValue as $item) {
            $this->rawValue = $item;
            $inner[] = $innerType->accept($this);
        }

        $this->rawValue = $listValue;

        return new ListInputedValue($list, $inner);
    }

    public static function convertArgumentSet(\Graphpinator\Argument\ArgumentSet $arguments, \stdClass $rawValue) : \stdClass
    {
        $rawValue = self::mergeRaw($rawValue, (object) $arguments->getRawDefaults());

        foreach ((array) $rawValue as $name => $temp) {
            if ($arguments->offsetExists($name)) {
                continue;
            }

            throw new \Graphpinator\Normalizer\Exception\UnknownArgument($name);
        }

        $inner = new \stdClass();

        foreach ($arguments as $argument) {
            $inner->{$argument->getName()} = self::convertArgument($argument, $rawValue->{$argument->getName()} ?? null);
        }

        return $inner;
    }

    public static function convertArgument(\Graphpinator\Argument\Argument $argument, mixed $rawValue) : ArgumentValue
    {
        $default = $argument->getDefaultValue();

        if ($rawValue === null && $default instanceof \Graphpinator\Value\ArgumentValue) {
            return $default;
        }

        $value = $argument->getType()->accept(new ConvertRawValueVisitor($rawValue));

        return new ArgumentValue($argument, $value, false);
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
