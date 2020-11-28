<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Value;

final class ArgumentValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Argument\Argument $argument;
    private \Graphpinator\Value\InputedValue $value;

    public function __construct(
        \Graphpinator\Argument\Argument $argument,
        \stdClass|array|string|int|float|bool|null $rawValue,
    )
    {
        $this->argument = $argument;
        $this->value = $rawValue === null && $argument->getDefaultValue() instanceof \Graphpinator\Value\InputedValue
            ? $argument->getDefaultValue()
            : $argument->getType()->createInputedValue($rawValue);

        $argument->validateConstraints($this->value);
    }

    public function getValue() : \Graphpinator\Value\InputedValue
    {
        return $this->value;
    }

    public function getRawValue() : \stdClass|array|string|int|float|bool|null
    {
        return $this->value->getRawValue();
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
