<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use Graphpinator\Typesystem\Contract\ExecutableDirective;
use Graphpinator\Value\ArgumentValueSet;

interface InlineFragmentLocation extends ExecutableDirective
{
    public function resolveInlineFragmentBefore(ArgumentValueSet $arguments) : SelectionDirectiveResult;

    public function resolveInlineFragmentAfter(ArgumentValueSet $arguments) : void;
}
