<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface MutationLocation extends ExecutableDefinition
{
    public function resolveMutationBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveMutationAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\TypeValue $typeValue,
    ) : string;
}
