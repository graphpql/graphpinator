<?php

declare(strict_types = 1);

namespace Graphpinator\Argument;

final class ArgumentValue
{
    use \Nette\SmartObject;

    protected \Graphpinator\Argument\Argument $argument;
    protected \Graphpinator\Value\InputedValue $value;

    public function __construct(\Graphpinator\Argument\Argument $argument, $rawValue)
    {
        $this->argument = $argument;
        $default = $argument->getDefaultValue();

        if ($rawValue === null && $default instanceof \Graphpinator\Value\InputedValue) {
            $this->value = $default;
        } else {
            $this->value = $argument->getType()->createInputedValue($rawValue);
        }

        $argument->validateConstraints($this->value);
    }

    public function getValue() : \Graphpinator\Value\InputedValue
    {
        return $this->value;
    }
}
