<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use Graphpinator\Normalizer\Variable\Variable;
use Graphpinator\Typesystem\Contract\ExecutableDirective;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\InputedValue;

interface VariableDefinitionLocation extends ExecutableDirective
{
    public function validateVariableUsage(Variable $variable, ArgumentValueSet $arguments) : bool;

    public function resolveVariableDefinition(ArgumentValueSet $arguments, InputedValue $variableValue) : void;
}
