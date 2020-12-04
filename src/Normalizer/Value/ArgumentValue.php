<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Value;

final class ArgumentValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Argument\Argument $argument;
    private \Graphpinator\Value\InputedValue $value;
    private bool $constraintValidated = false;

    private function __construct(\Graphpinator\Argument\Argument $argument, \Graphpinator\Value\InputedValue $value)
    {
        $this->argument = $argument;
        $this->value = $value;
    }

    public static function fromRaw(
        \Graphpinator\Argument\Argument $argument,
        \stdClass|array|string|int|float|bool|null $rawValue,
    ) : self
    {
        $default = $argument->getDefaultValue();

        $value = $rawValue === null && $default instanceof \Graphpinator\Value\InputedValue
            ? $default
            : $argument->getType()->createInputedValue($rawValue);

        $argument->validateConstraints($value);

        return new self($argument, $value);
    }

    public static function fromParsed(
        \Graphpinator\Argument\Argument $argument,
        \Graphpinator\Parser\Value\Value $value,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : self
    {
        $val = $value->createInputedValue($argument->getType(), $variableSet);
        $default = $argument->getDefaultValue();

        if ($val instanceof \Graphpinator\Value\NullInputedValue && $default instanceof \Graphpinator\Value\InputedValue) {
            return new self($argument, $default);
        }

        return new self($argument, $val);
    }

    public function getValue() : \Graphpinator\Value\InputedValue
    {
        return $this->value;
    }

    public function getArgument() : \Graphpinator\Argument\Argument
    {
        return $this->argument;
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : void
    {
        $this->value->applyVariables($variables);
    }
}
