<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use Graphpinator\Typesystem\Contract\TypeSystemDirective;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\InputValue;

interface InputObjectLocation extends TypeSystemDirective
{
    public function validateInputUsage(InputType $inputType, ArgumentValueSet $arguments) : bool;

    public function resolveInputObject(ArgumentValueSet $arguments, InputValue $inputValue) : void;
}
