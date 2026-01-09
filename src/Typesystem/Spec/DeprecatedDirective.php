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
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Value\ArgumentValue;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\Contract\Value;
use Graphpinator\Value\FieldValue;

#[Description('Built-in deprecated directive')]
final class DeprecatedDirective extends Directive implements
    FieldDefinitionLocation,
    EnumItemLocation,
    ArgumentDefinitionLocation
{
    public const DEFAULT_MESSAGE = 'No longer supported';
    protected const NAME = 'deprecated';

    #[\Override]
    public static function isPure() : bool
    {
        return true;
    }

    #[\Override]
    public function validateFieldUsage(Field $field, ArgumentValueSet $arguments) : bool
    {
        return true;
    }

    #[\Override]
    public function validateVariance(?ArgumentValueSet $biggerSet, ?ArgumentValueSet $smallerSet) : void
    {
        // nothing here
    }

    #[\Override]
    public function resolveFieldDefinitionStart(ArgumentValueSet $arguments, Value $parentValue) : void
    {
        // nothing here
    }

    #[\Override]
    public function resolveFieldDefinitionBefore(ArgumentValueSet $arguments, Value $parentValue, ArgumentValueSet $fieldArguments) : void
    {
        // nothing here
    }

    #[\Override]
    public function resolveFieldDefinitionAfter(ArgumentValueSet $arguments, Value $resolvedValue, ArgumentValueSet $fieldArguments) : void
    {
        // nothing here
    }

    #[\Override]
    public function resolveFieldDefinitionValue(ArgumentValueSet $arguments, FieldValue $fieldValue) : void
    {
        // nothing here
    }

    #[\Override]
    public function validateArgumentUsage(Argument $argument, ArgumentValueSet $arguments) : bool
    {
        if ($argument->getType() instanceof NotNullType) {
            return $argument->getDefaultValue() instanceof ArgumentValue;
        }

        return true;
    }

    #[\Override]
    public function resolveArgumentDefinition(ArgumentValueSet $arguments, ArgumentValue $argumentValue) : void
    {
        // nothing here
    }

    #[\Override]
    protected function getFieldDefinition() : ArgumentSet
    {
        return new ArgumentSet([
            Argument::create('reason', Container::String()->notNull())
                ->setDefaultValue(self::DEFAULT_MESSAGE),
        ]);
    }
}
