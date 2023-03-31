<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

interface FragmentSpreadLocation extends \Graphpinator\Typesystem\Contract\ExecutableDirective
{
    public function resolveFragmentSpreadBefore(\Graphpinator\Value\ArgumentValueSet $arguments) : SelectionDirectiveResult;

    public function resolveFragmentSpreadAfter(\Graphpinator\Value\ArgumentValueSet $arguments) : void;
}
