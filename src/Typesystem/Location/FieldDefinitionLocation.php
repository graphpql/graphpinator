<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use Graphpinator\Typesystem\Contract\TypeSystemDirective;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\Contract\Value;
use Graphpinator\Value\FieldValue;

interface FieldDefinitionLocation extends TypeSystemDirective
{
    public function validateFieldUsage(Field $field, ArgumentValueSet $arguments) : bool;

    public function validateVariance(?ArgumentValueSet $biggerSet, ?ArgumentValueSet $smallerSet) : void;

    public function resolveFieldDefinitionStart(ArgumentValueSet $arguments, Value $parentValue) : void;

    public function resolveFieldDefinitionBefore(ArgumentValueSet $arguments, Value $parentValue, ArgumentValueSet $fieldArguments) : void;

    public function resolveFieldDefinitionAfter(ArgumentValueSet $arguments, Value $resolvedValue, ArgumentValueSet $fieldArguments) : void;

    public function resolveFieldDefinitionValue(ArgumentValueSet $arguments, FieldValue $fieldValue) : void;
}
