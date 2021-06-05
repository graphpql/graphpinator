<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

interface MutationLocation extends \Graphpinator\Typesystem\Contract\ExecutableDirective
{
    public function resolveMutationBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveMutationAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\TypeValue $typeValue,
    ) : string;
}
