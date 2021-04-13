<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface ArgumentDefinitionLocation extends TypeSystemDefinition
{
    public function validateArgumentUsage(
        \Graphpinator\Argument\Argument $argument,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool;

    public function validateVariance(
        ?\Graphpinator\Value\ArgumentValueSet $biggerSet,
        ?\Graphpinator\Value\ArgumentValueSet $smallerSet,
    ) : void;

    public function resolveArgumentDefinition(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\ArgumentValue $argumentValue,
    ) : void;

    public static function isPure() : bool;
}
