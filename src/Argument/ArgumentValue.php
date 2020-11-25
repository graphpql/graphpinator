<?php

declare(strict_types = 1);

namespace Graphpinator\Argument;

final class ArgumentValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Argument\Argument $argument;
    private \Graphpinator\Value\InputedValue $value;

    private function __construct(\Graphpinator\Argument\Argument $argument, \Graphpinator\Value\InputedValue $value)
    {
        $this->argument = $argument;
        $this->value = $value;

        $argument->validateConstraints($this->value);
    }

    public static function fromRaw(Argument $argument, mixed $rawValue) : self
    {
        $default = $argument->getDefaultValue();

        $value = $rawValue === null && $default instanceof \Graphpinator\Value\InputedValue
            ? $default
            : $argument->getType()->createInputedValue($rawValue);

        return new self($argument, $value);
    }

    public static function fromInputed(Argument $argument, \Graphpinator\Value\InputedValue $value) : self
    {
        if (!$value->getType()->isInstanceOf($argument->getType())) {
            throw new \Exception();
        }

        return new self($argument, $value);
    }

    public function getArgument() : Argument
    {
        return $this->argument;
    }

    public function getValue() : \Graphpinator\Value\InputedValue
    {
        return $this->value;
    }
}
