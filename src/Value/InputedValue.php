<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Contract\Inputable;

interface InputedValue extends Value
{
    #[\Override]
    public function getRawValue(bool $forResolvers = false) : mixed;

    #[\Override]
    public function getType() : Inputable;

    /**
     * Function used to replace variable references with concrete values before query execution.
     */
    public function applyVariables(VariableValueSet $variables) : void;

    /**
     * Function used to recursively call resolution of nested non-pure argument directives.
     */
    public function resolveRemainingDirectives() : void;

    /**
     * Function used to compare argument values in field merging.
     */
    public function isSame(Value $compare) : bool;

    /**
     * Function used to print value in introspection.
     */
    public function printValue() : string;
}
