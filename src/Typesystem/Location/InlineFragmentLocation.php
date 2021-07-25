<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

interface InlineFragmentLocation extends \Graphpinator\Typesystem\Contract\ExecutableDirective
{
    public function resolveInlineFragmentBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveInlineFragmentAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;
}
