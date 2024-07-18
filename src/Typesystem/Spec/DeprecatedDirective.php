<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Directive;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Typesystem\Location\ArgumentDefinitionLocation;
use Graphpinator\Typesystem\Location\EnumItemLocation;
use Graphpinator\Typesystem\Location\FieldDefinitionLocation;
use Graphpinator\Value\ArgumentValue;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\FieldValue;
use Graphpinator\Value\ResolvedValue;

#[Description('Built-in deprecated directive')]
final class DeprecatedDirective extends Directive implements
    FieldDefinitionLocation,
    EnumItemLocation,
    ArgumentDefinitionLocation
{
    protected const NAME = 'deprecated';

    public static function isPure() : bool
    {
        return true;
    }

    public function validateFieldUsage(Field $field, ArgumentValueSet $arguments) : bool
    {
        return true;
    }

    public function validateVariance(?ArgumentValueSet $biggerSet, ?ArgumentValueSet $smallerSet) : void
    {
        // nothing here
    }

    public function resolveFieldDefinitionStart(ArgumentValueSet $arguments, ResolvedValue $parentValue) : void
    {
        // nothing here
    }

    public function resolveFieldDefinitionBefore(ArgumentValueSet $arguments, ResolvedValue $parentValue, ArgumentValueSet $fieldArguments) : void
    {
        // nothing here
    }

    public function resolveFieldDefinitionAfter(ArgumentValueSet $arguments, ResolvedValue $resolvedValue, ArgumentValueSet $fieldArguments) : void
    {
        // nothing here
    }

    public function resolveFieldDefinitionValue(ArgumentValueSet $arguments, FieldValue $fieldValue) : void
    {
        // nothing here
    }

    public function validateArgumentUsage(Argument $argument, ArgumentValueSet $arguments) : bool
    {
        return true;
    }

    public function resolveArgumentDefinition(ArgumentValueSet $arguments, ArgumentValue $argumentValue) : void
    {
        // nothing here
    }

    protected function getFieldDefinition() : ArgumentSet
    {
        return new ArgumentSet([
            new Argument('reason', Container::String()),
        ]);
    }
}
