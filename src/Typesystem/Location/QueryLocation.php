<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use Graphpinator\Typesystem\Contract\ExecutableDirective;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\TypeValue;

interface QueryLocation extends ExecutableDirective
{
    public function resolveQueryBefore(ArgumentValueSet $arguments) : void;

    public function resolveQueryAfter(ArgumentValueSet $arguments, TypeValue $typeValue) : void;
}
