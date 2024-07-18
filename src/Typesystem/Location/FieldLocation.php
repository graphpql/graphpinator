<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use Graphpinator\Typesystem\Contract\ExecutableDirective;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\FieldValue;

interface FieldLocation extends ExecutableDirective
{
    public function validateFieldUsage(Field $field, ArgumentValueSet $arguments) : bool;

    public function resolveFieldBefore(ArgumentValueSet $arguments) : SelectionDirectiveResult;

    public function resolveFieldAfter(ArgumentValueSet $arguments, FieldValue $fieldValue) : SelectionDirectiveResult;
}
