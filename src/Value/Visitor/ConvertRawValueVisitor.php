<?php

declare(strict_types = 1);

namespace Graphpinator\Value\Visitor;

use Graphpinator\Common\Path;
use Graphpinator\Normalizer\Exception\UnknownArgument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Contract\TypeVisitor;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Typesystem\Visitor\IsInputableVisitor;
use Graphpinator\Value\ArgumentValue;
use Graphpinator\Value\Contract\InputedValue;
use Graphpinator\Value\EnumValue;
use Graphpinator\Value\Exception\InvalidValue;
use Graphpinator\Value\Exception\ValueCannotBeNull;
use Graphpinator\Value\Exception\ValueCannotBeOmitted;
use Graphpinator\Value\InputValue;
use Graphpinator\Value\ListValue;
use Graphpinator\Value\NullValue;
use Graphpinator\Value\ScalarValue;

/**
 * @implements TypeVisitor<InputedValue>
 */
final readonly class ConvertRawValueVisitor implements TypeVisitor
{
    public function __construct(
        private mixed $rawValue,
        private Path $path,
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

    #[\Override]
    public function visitType(Type $type) : never
    {
        throw new \LogicException(); // @codeCoverageIgnore
    }

    #[\Override]
    public function visitInterface(InterfaceType $interface) : never
    {
        throw new \LogicException(); // @codeCoverageIgnore
    }

    #[\Override]
    public function visitUnion(UnionType $union) : never
    {
        throw new \LogicException(); // @codeCoverageIgnore
    }

    #[\Override]
    public function visitInput(InputType $input) : InputedValue
    {
        if ($this->rawValue === null) {
            return new NullValue($input);
        }

        if (!$this->rawValue instanceof \stdClass) {
            throw new InvalidValue($input, $this->rawValue, true);
        }

        return new InputValue($input, self::convertArgumentSet($input->getArguments(), $this->rawValue, $this->path, true));
    }

    #[\Override]
    public function visitScalar(ScalarType $scalar) : InputedValue
    {
        return $this->rawValue === null
            ? new NullValue($scalar)
            : new ScalarValue($scalar, $this->rawValue, true);
    }

    #[\Override]
    public function visitEnum(EnumType $enum) : InputedValue
    {
        return $this->rawValue === null
            ? new NullValue($enum)
            : new EnumValue($enum, $this->rawValue, true);
    }

    #[\Override]
    public function visitNotNull(NotNullType $notNull) : InputedValue
    {
        return $this->rawValue === null
            ? throw new ValueCannotBeNull(true)
            : $notNull->getInnerType()->accept($this);
    }

    #[\Override]
    public function visitList(ListType $list) : InputedValue
    {
        if ($this->rawValue === null) {
            return new NullValue($list);
        }

        $coercedValue = \is_array($this->rawValue)
            ? $this->rawValue
            : [$this->rawValue];

        $innerType = $list->getInnerType();
        \assert($innerType->accept(new IsInputableVisitor()));

        $inner = [];

        foreach ($coercedValue as $index => $rawValue) {
            $this->path->add($index . ' <list index>');
            $inner[] = $innerType->accept(new self($rawValue, $this->path));
            $this->path->pop();
        }

        return new ListValue($list, $inner);
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
