<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ConvertRawValueVisitor implements \Graphpinator\Typesystem\Contract\TypeVisitor
{
    use \Nette\SmartObject;

    public function __construct(
        private mixed $rawValue,
        private \Graphpinator\Common\Path $path,
    )
    {
    }

    public static function convertArgumentSet(
        \Graphpinator\Typesystem\Argument\ArgumentSet $arguments,
        \stdClass $rawValue,
        \Graphpinator\Common\Path $path,
        bool $canBeOmitted = false,
    ) : \stdClass
    {
        $rawValue = self::mergeRaw($rawValue, (object) $arguments->getRawDefaults());

        foreach ((array) $rawValue as $name => $temp) {
            if ($arguments->offsetExists($name)) {
                continue;
            }

            throw new \Graphpinator\Normalizer\Exception\UnknownArgument((string) $name);
        }

        $inner = new \stdClass();

        foreach ($arguments as $argument) {
            $path->add($argument->getName() . ' <argument>');

            if (\property_exists($rawValue, $argument->getName())) {
                $inner->{$argument->getName()} = new ArgumentValue(
                    $argument,
                    $argument->getType()->accept(new ConvertRawValueVisitor($rawValue->{$argument->getName()}, $path)),
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
                    $argument->getType()->accept(new ConvertRawValueVisitor(null, $path)),
                    false,
                );
            } elseif ($argument->getType() instanceof \Graphpinator\Typesystem\NotNullType) {
                throw new \Graphpinator\Exception\Value\ValueCannotBeOmitted();
            }

            $path->pop();
        }

        return $inner;
    }

    public function visitType(\Graphpinator\Typesystem\Type $type) : mixed
    {
        // nothing here
    }

    public function visitInterface(\Graphpinator\Typesystem\InterfaceType $interface) : mixed
    {
        // nothing here
    }

    public function visitUnion(\Graphpinator\Typesystem\UnionType $union) : mixed
    {
        // nothing here
    }

    public function visitInput(\Graphpinator\Typesystem\InputType $input) : InputedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($input);
        }

        if (!$this->rawValue instanceof \stdClass) {
            throw new \Graphpinator\Exception\Value\InvalidValue($input->getName(), $this->rawValue, true);
        }

        return new InputValue($input, self::convertArgumentSet($input->getArguments(), $this->rawValue, $this->path, true));
    }

    public function visitScalar(\Graphpinator\Typesystem\ScalarType $scalar) : InputedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($scalar);
        }

        $this->rawValue = $scalar->coerceValue($this->rawValue);

        return new \Graphpinator\Value\ScalarValue($scalar, $this->rawValue, true);
    }

    public function visitEnum(\Graphpinator\Typesystem\EnumType $enum) : InputedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($enum);
        }

        if (\is_object($this->rawValue) && \is_string($enum->getEnumClass())) {
            return new \Graphpinator\Value\EnumValue($enum, $this->rawValue->value, true);
        }

        return new \Graphpinator\Value\EnumValue($enum, $this->rawValue, true);
    }

    public function visitNotNull(\Graphpinator\Typesystem\NotNullType $notNull) : InputedValue
    {
        $value = $notNull->getInnerType()->accept($this);

        if ($value instanceof \Graphpinator\Value\NullValue) {
            throw new \Graphpinator\Exception\Value\ValueCannotBeNull(true);
        }

        return $value;
    }

    public function visitList(\Graphpinator\Typesystem\ListType $list) : InputedValue
    {
        if ($this->rawValue === null) {
            return new \Graphpinator\Value\NullInputedValue($list);
        }

        if (!\is_array($this->rawValue)) {
            $this->rawValue = [$this->rawValue];
        }

        $innerType = $list->getInnerType();
        \assert($innerType instanceof \Graphpinator\Typesystem\Contract\Inputable);

        $inner = [];
        $listValue = $this->rawValue;

        foreach ($listValue as $index => $rawValue) {
            $this->path->add($index . ' <list index>');
            $this->rawValue = $rawValue;
            $inner[] = $innerType->accept($this);
            $this->path->pop();
        }

        $this->rawValue = $listValue;

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
