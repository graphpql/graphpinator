<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface QueryLocation extends ExecutableDefinition
{
    public function resolveQueryBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveQueryAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\TypeValue $typeValue,
    ) : string;
}
