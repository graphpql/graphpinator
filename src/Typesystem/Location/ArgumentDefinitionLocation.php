<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

interface ArgumentDefinitionLocation extends \Graphpinator\Typesystem\Contract\TypesystemDirective
{
    public static function isPure() : bool;

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
}
