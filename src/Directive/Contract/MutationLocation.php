<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface MutationLocation extends ExecutableDefinition
{
    public function resolveMutationBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveMutationAfter(
        \Graphpinator\Value\TypeValue $typeValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;
}
