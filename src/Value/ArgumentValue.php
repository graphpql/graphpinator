<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ArgumentValue
{
    use \Nette\SmartObject;

    private function __construct(
        private \Graphpinator\Argument\Argument $argument,
        private \Graphpinator\Value\InputedValue $value,
        private bool $hasVariables,
    )
    {
        if (!$this->hasVariables) {
            $this->resolveDirectives();
        }
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

        return new self($argument, $value, false);
    }

    public static function fromInputed(
        \Graphpinator\Argument\Argument $argument,
        \Graphpinator\Value\InputedValue $value,
    ) : self
    {
        if ($value->getType()->isInstanceOf($argument->getType())) {
            return new self($argument, $value, true);
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
            return new self($argument, $default, false);
        }

        return new self($argument, $value, true);
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
        if ($this->hasVariables) {
            $this->value->applyVariables($variables);
            $this->resolveDirectives();
        }
    }

    private function resolveDirectives() : void
    {
        foreach ($this->argument->getDirectiveUsages() as $directiveUsage) {
            $directiveUsage->getDirective()->resolveArgumentDefinition($directiveUsage->getArgumentValues(), $this);
        }
    }
}
