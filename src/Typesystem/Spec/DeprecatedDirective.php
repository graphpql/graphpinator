<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use \Graphpinator\Value\ArgumentValueSet;
use \Graphpinator\Value\ResolvedValue;

final class DeprecatedDirective extends \Graphpinator\Typesystem\Directive implements
    \Graphpinator\Typesystem\Location\FieldDefinitionLocation,
    \Graphpinator\Typesystem\Location\EnumItemLocation,
    \Graphpinator\Typesystem\Location\ArgumentDefinitionLocation
{
    protected const NAME = 'deprecated';
    protected const DESCRIPTION = 'Built-in deprecated directive.';

    public static function isPure() : bool
    {
        return true;
    }

    public function validateFieldUsage(
        \Graphpinator\Typesystem\Field\Field $field,
        ArgumentValueSet $arguments,
    ) : bool
    {
        return true;
    }

    public function validateVariance(
        ?ArgumentValueSet $biggerSet,
        ?ArgumentValueSet $smallerSet,
    ) : void
    {
        // nothing here
    }

    public function resolveFieldDefinitionStart(
        ArgumentValueSet $arguments,
        ResolvedValue $parentValue,
    ) : void
    {
        // nothing here
    }

    public function resolveFieldDefinitionBefore(
        ArgumentValueSet $arguments,
        ResolvedValue $parentValue,
        ArgumentValueSet $fieldArguments,
    ) : void
    {
        // nothing here
    }

    public function resolveFieldDefinitionAfter(
        ArgumentValueSet $arguments,
        ResolvedValue $resolvedValue,
        ArgumentValueSet $fieldArguments,
    ) : void
    {
        // nothing here
    }

    public function resolveFieldDefinitionValue(
        ArgumentValueSet $arguments,
        \Graphpinator\Value\FieldValue $fieldValue,
    ) : void
    {
        // nothing here
    }

    public function validateArgumentUsage(
        \Graphpinator\Typesystem\Argument\Argument $argument,
        ArgumentValueSet $arguments,
    ) : bool
    {
        return true;
    }

    public function resolveArgumentDefinition(
        ArgumentValueSet $arguments,
        \Graphpinator\Value\ArgumentValue $argumentValue,
    ) : void
    {
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
    {
        return new \Graphpinator\Typesystem\Argument\ArgumentSet([
            new \Graphpinator\Typesystem\Argument\Argument('reason', \Graphpinator\Typesystem\Container::String()),
        ]);
    }
}
