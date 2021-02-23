<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Contract;

interface SubscriptionLocation extends ExecutableDefinition
{
    public function resolveSubscriptionBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveSubscriptionAfter(
        \Graphpinator\Value\TypeValue $typeValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;
}
