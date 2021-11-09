<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use \Graphpinator\Typesystem\Contract\ExecutableDirective;

interface InlineFragmentLocation extends ExecutableDirective
{
    public function resolveInlineFragmentBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveInlineFragmentAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;
}
