<?php

declare(strict_types = 1);

namespace Graphpinator\Argument;

final class ArgumentValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Argument\Argument $argument;
    private \Graphpinator\Value\InputedValue $value;

    public function __construct(\Graphpinator\Argument\Argument $argument, $rawValue)
    {
        $this->argument = $argument;
        $default = $argument->getDefaultValue();

        $this->value = $rawValue === null && $default instanceof \Graphpinator\Value\InputedValue
            ? $default
            : $argument->getType()->createInputedValue($rawValue);

        $argument->validateConstraints($this->value);
    }

    public function getValue() : \Graphpinator\Value\InputedValue
    {
        return $this->value;
    }
}
