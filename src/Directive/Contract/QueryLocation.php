<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface QueryLocation extends ExecutableDefinition
{
    public function resolveQueryBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveQueryAfter(
        \Graphpinator\Value\TypeValue $typeValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;
}
