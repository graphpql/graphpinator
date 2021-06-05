<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

interface ObjectLocation extends \Graphpinator\Typesystem\Contract\TypesystemDirective
{
    public function validateObjectUsage(
        \Graphpinator\Type\Type|\Graphpinator\Type\InterfaceType $type,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool;

    public function resolveObject(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\TypeValue $typeValue,
    ) : void;
}
