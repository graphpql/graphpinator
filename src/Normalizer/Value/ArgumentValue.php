<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Value;

interface ArgumentValue
{
    public function getValue() : \Graphpinator\Value\InputedValue;

    public function getArgument() : \Graphpinator\Argument\Argument;

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : void;
}
