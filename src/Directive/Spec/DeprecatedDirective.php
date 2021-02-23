<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Spec;

final class DeprecatedDirective extends \Graphpinator\Directive\Directive
    implements \Graphpinator\Directive\Contract\FieldDefinitionLocation, \Graphpinator\Directive\Contract\EnumItemLocation
{
    protected const NAME = 'deprecated';
    protected const DESCRIPTION = 'Built-in deprecated directive.';

    public function __construct()
    {
        parent::__construct(
            [
                \Graphpinator\Directive\TypeSystemDirectiveLocation::FIELD_DEFINITION,
                \Graphpinator\Directive\TypeSystemDirectiveLocation::ENUM_VALUE,
            ],
            false,
        );
    }

    public function validateType(
        \Graphpinator\Type\Contract\Definition $definition,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool
    {
        return true;
    }

    public function validateVariance(
        ?\Graphpinator\Value\ArgumentValueSet $biggerSet,
        ?\Graphpinator\Value\ArgumentValueSet $smallerSet,
    ) : void
    {
        // nothing here
    }

    public function resolveFieldDefinitionBefore(
        \Graphpinator\Value\ResolvedValue $parentValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        // nothing here
    }

    public function resolveFieldDefinitionAfter(
        \Graphpinator\Value\ResolvedValue $resolvedValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        // nothing here
    }

    public function resolveFieldDefinitionValue(
        \Graphpinator\Value\FieldValue $fieldValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        // nothing here
    }

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            new \Graphpinator\Argument\Argument('reason', \Graphpinator\Container\Container::String()),
        ]);
    }
}
