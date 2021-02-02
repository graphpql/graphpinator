<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

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
        string|int|float|bool|null|array|\stdClass|\Psr\Http\Message\UploadedFileInterface $rawValue,
    ) : self
    {
        $default = $argument->getDefaultValue();

        $value = $rawValue === null && $default instanceof \Graphpinator\Value\InputedValue
            ? $default
            : $argument->getType()->createInputedValue($rawValue);

        return new self($argument, $value);
    }

    public static function fromInputed(
        \Graphpinator\Argument\Argument $argument,
        \Graphpinator\Value\InputedValue $value,
    ) : self
    {
        if ($value->getType()->isInstanceOf($argument->getType())) {
            return new self($argument, $value);
        }

        throw new \Exception();
    }

    public static function fromParsed(
        \Graphpinator\Argument\Argument $argument,
        \Graphpinator\Parser\Value\Value $parsedValue,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : self
    {
        $value = $parsedValue->createInputedValue($argument->getType(), $variableSet);
        $default = $argument->getDefaultValue();

        if ($value instanceof \Graphpinator\Value\NullInputedValue && $default instanceof \Graphpinator\Value\InputedValue) {
            return new self($argument, $default);
        }

        return new self($argument, $value);
    }

    public function getValue() : \Graphpinator\Value\InputedValue
    {
        return $this->value;
    }

    public function getArgument() : \Graphpinator\Argument\Argument
    {
        return $this->argument;
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
    {
        $this->value->applyVariables($variables);

        foreach ($this->argument->getDirectives() as $directive) {
            $directive->getDirective()->resolveArgumentDefinition($this, $directive->getArguments());
        }
    }
}
