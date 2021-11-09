<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use \Graphpinator\Typesystem\Contract\ExecutableDirective;

interface SubscriptionLocation extends ExecutableDirective
{
    public function resolveSubscriptionBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;

    public function resolveSubscriptionAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\TypeValue $typeValue,
    ) : void;
}
