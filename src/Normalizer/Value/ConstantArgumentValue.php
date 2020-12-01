<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Value;

final class ConstantArgumentValue implements \Graphpinator\Normalizer\Value\ArgumentValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Argument\Argument $argument;
    private \Graphpinator\Value\InputedValue $value;

    public function __construct(
        \Graphpinator\Argument\Argument $argument,
        \stdClass|array|string|int|float|bool|null $rawValue,
    )
    {
        $default = $argument->getDefaultValue();

        $this->argument = $argument;
        $this->value = $rawValue === null && $default instanceof \Graphpinator\Value\InputedValue
            ? $default
            : $argument->getType()->createInputedValue($rawValue);

        $this->argument->validateConstraints($this->value);
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
