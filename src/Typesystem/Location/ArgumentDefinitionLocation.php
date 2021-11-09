<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use \Graphpinator\Typesystem\Contract\TypeSystemDirective;
use \Graphpinator\Value\ArgumentValueSet;

interface ArgumentDefinitionLocation extends TypeSystemDirective
{
    public static function isPure() : bool;

    public function validateArgumentUsage(
        \Graphpinator\Typesystem\Argument\Argument $argument,
        ArgumentValueSet $arguments,
    ) : bool;

    public function validateVariance(
        ?ArgumentValueSet $biggerSet,
        ?ArgumentValueSet $smallerSet,
    ) : void;

    public function resolveArgumentDefinition(
        ArgumentValueSet $arguments,
        \Graphpinator\Value\ArgumentValue $argumentValue,
    ) : void;
}
