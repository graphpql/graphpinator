<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

interface VariableDefinitionLocation extends \Graphpinator\Typesystem\Contract\ExecutableDirective
{
    public function validateVariableUsage(\Graphpinator\Normalizer\Variable\Variable $variable, \Graphpinator\Value\ArgumentValueSet $arguments) : bool;

    public function resolveVariableDefinition(\Graphpinator\Value\ArgumentValueSet $arguments, \Graphpinator\Value\InputedValue $variableValue) : void;
}
