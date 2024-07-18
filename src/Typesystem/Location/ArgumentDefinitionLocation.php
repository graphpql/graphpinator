<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Contract\TypeSystemDirective;
use Graphpinator\Value\ArgumentValue;
use Graphpinator\Value\ArgumentValueSet;

interface ArgumentDefinitionLocation extends TypeSystemDirective
{
    public static function isPure() : bool;

    public function validateArgumentUsage(Argument $argument, ArgumentValueSet $arguments) : bool;

    public function validateVariance(?ArgumentValueSet $biggerSet, ?ArgumentValueSet $smallerSet) : void;

    public function resolveArgumentDefinition(ArgumentValueSet $arguments, ArgumentValue $argumentValue) : void;
}
