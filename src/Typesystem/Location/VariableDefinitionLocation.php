<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use \Graphpinator\Typesystem\Contract\ExecutableDirective;

interface VariableDefinitionLocation extends ExecutableDirective
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
