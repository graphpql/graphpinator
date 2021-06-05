<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

interface ObjectLocation extends \Graphpinator\Typesystem\Contract\TypeSystemDirective
{
    public function validateObjectUsage(
        \Graphpinator\Typesystem\Type|\Graphpinator\Typesystem\InterfaceType $type,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool;

    public function resolveObject(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\TypeValue $typeValue,
    ) : void;
}
