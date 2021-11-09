<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use \Graphpinator\Typesystem\Contract\TypeSystemDirective;

interface InputObjectLocation extends TypeSystemDirective
{
    public function validateInputUsage(
        \Graphpinator\Typesystem\InputType $inputType,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool;

    public function resolveInputObject(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\InputValue $inputValue,
    ) : void;
}
