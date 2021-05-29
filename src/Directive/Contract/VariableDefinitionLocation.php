<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface VariableDefinitionLocation extends ExecutableDefinition
{
    public function validateVariableUsage(
        \Graphpinator\Normalizer\Variable\Variable $variable,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool;

    public function resolveVariableDefinition(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\InputedValue $variableValue,
    ) : void;
}
