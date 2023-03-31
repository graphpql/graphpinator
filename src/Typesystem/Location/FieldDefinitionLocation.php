<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Location;

use \Graphpinator\Value\ArgumentValueSet;
use \Graphpinator\Value\ResolvedValue;

interface FieldDefinitionLocation extends \Graphpinator\Typesystem\Contract\TypeSystemDirective
{
    public function validateFieldUsage(\Graphpinator\Typesystem\Field\Field $field, ArgumentValueSet $arguments) : bool;

    public function validateVariance(?ArgumentValueSet $biggerSet, ?ArgumentValueSet $smallerSet) : void;

    public function resolveFieldDefinitionStart(ArgumentValueSet $arguments, ResolvedValue $parentValue) : void;

    public function resolveFieldDefinitionBefore(ArgumentValueSet $arguments, ResolvedValue $parentValue, ArgumentValueSet $fieldArguments) : void;

    public function resolveFieldDefinitionAfter(ArgumentValueSet $arguments, ResolvedValue $resolvedValue, ArgumentValueSet $fieldArguments) : void;

    public function resolveFieldDefinitionValue(ArgumentValueSet $arguments, \Graphpinator\Value\FieldValue $fieldValue) : void;
}
