<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use \Graphpinator\Value\ArgumentValueSet;

interface FieldLocation extends \Graphpinator\Typesystem\Contract\ExecutableDirective
{
    public function validateFieldUsage(\Graphpinator\Typesystem\Field\Field $field, ArgumentValueSet $arguments) : bool;

    public function resolveFieldBefore(ArgumentValueSet $arguments) : SelectionDirectiveResult;

    public function resolveFieldAfter(ArgumentValueSet $arguments, \Graphpinator\Value\FieldValue $fieldValue) : SelectionDirectiveResult;
}
