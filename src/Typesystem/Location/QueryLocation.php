<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

interface QueryLocation extends \Graphpinator\Typesystem\Contract\ExecutableDirective
{
    public function resolveQueryBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveQueryAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\TypeValue $typeValue,
    ) : string;
}
