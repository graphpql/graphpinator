<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use Graphpinator\Typesystem\Contract\ExecutableDirective;
use Graphpinator\Value\ArgumentValueSet;

interface FragmentSpreadLocation extends ExecutableDirective
{
    public function resolveFragmentSpreadBefore(ArgumentValueSet $arguments) : SelectionDirectiveResult;

    public function resolveFragmentSpreadAfter(ArgumentValueSet $arguments) : void;
}
