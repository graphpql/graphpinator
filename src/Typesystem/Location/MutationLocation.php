<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use Graphpinator\Typesystem\Contract\ExecutableDirective;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\TypeValue;

interface MutationLocation extends ExecutableDirective
{
    public function resolveMutationBefore(ArgumentValueSet $arguments) : void;

    public function resolveMutationAfter(ArgumentValueSet $arguments, TypeValue $typeValue) : void;
}
