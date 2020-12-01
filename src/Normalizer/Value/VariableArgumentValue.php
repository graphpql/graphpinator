<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Value;

final class VariableArgumentValue implements \Graphpinator\Normalizer\Value\ArgumentValue
{
    use \Nette\SmartObject;

    private \Graphpinator\Argument\Argument $argument;
    private \Graphpinator\Value\InputedValue $value;

    public function __construct(
        \Graphpinator\Argument\Argument $argument,
        \Graphpinator\Parser\Value\Value $value,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    )
    {
        $this->argument = $argument;
        $this->value = $value->createInputedValue($argument->getType(), $variableSet);
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
        $this->argument->validateConstraints($this->value);
    }
}
