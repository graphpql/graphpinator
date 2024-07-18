<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use Graphpinator\Typesystem\Contract\ExecutableDirective;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\TypeValue;

interface SubscriptionLocation extends ExecutableDirective
{
    public function resolveSubscriptionBefore(ArgumentValueSet $arguments) : void;

    public function resolveSubscriptionAfter(ArgumentValueSet $arguments, TypeValue $typeValue) : void;
}
