<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use Graphpinator\Typesystem\Contract\TypeSystemDirective;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\TypeValue;

interface ObjectLocation extends TypeSystemDirective
{
    public function validateObjectUsage(Type|InterfaceType $type, ArgumentValueSet $arguments) : bool;

    public function resolveObject(ArgumentValueSet $arguments, TypeValue $typeValue) : void;
}
