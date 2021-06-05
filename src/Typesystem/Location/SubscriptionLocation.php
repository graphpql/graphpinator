<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

interface SubscriptionLocation extends \Graphpinator\Typesystem\Contract\ExecutableDirective
{
    public function resolveSubscriptionBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string;

    public function resolveSubscriptionAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\TypeValue $typeValue,
    ) : string;
}
