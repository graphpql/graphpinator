<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ArgumentValue
{
    use \Nette\SmartObject;

    protected \Graphpinator\Argument\Argument $argument;
    protected \Graphpinator\Value\InputableValue $value;

    public function __construct($rawValue, \Graphpinator\Argument\Argument $argument)
    {
        $this->argument = $argument;
        $default = $argument->getDefaultValue();

        if ($rawValue === null && $default instanceof InputableValue) {
            $this->value = $default;
        } else {
            $this->value = $argument->getType()->createInputableValue($rawValue);
        }

        $argument->validateConstraints($this->value);
    }

    public function getValue() : InputableValue
    {
        return $this->value;
    }
}
