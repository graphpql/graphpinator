<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

final class IncludeDirective extends \Graphpinator\Typesystem\Directive implements
    \Graphpinator\Typesystem\Location\FieldLocation,
    \Graphpinator\Typesystem\Location\InlineFragmentLocation,
    \Graphpinator\Typesystem\Location\FragmentSpreadLocation
{
    protected const NAME = 'include';
    protected const DESCRIPTION = 'Built-in include directive.';

    public function validateFieldUsage(
        \Graphpinator\Typesystem\Field\Field $field,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool
    {
        return true;
    }

    public function resolveFieldBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string
    {
        return $arguments->offsetGet('if')->getValue()->getRawValue()
            ? \Graphpinator\Typesystem\Location\FieldLocation::NONE
            : \Graphpinator\Typesystem\Location\FieldLocation::SKIP;
    }

    public function resolveFieldAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
        \Graphpinator\Value\FieldValue $fieldValue,
    ) : string
    {
        return \Graphpinator\Typesystem\Location\FieldLocation::NONE;
    }

    public function resolveFragmentSpreadBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string
    {
        return $this->resolveFieldBefore($arguments);
    }

    public function resolveFragmentSpreadAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string
    {
        return \Graphpinator\Typesystem\Location\FieldLocation::NONE;
    }

    public function resolveInlineFragmentBefore(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string
    {
        return $this->resolveFieldBefore($arguments);
    }

    public function resolveInlineFragmentAfter(
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string
    {
        return \Graphpinator\Typesystem\Location\FieldLocation::NONE;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
    {
        return new \Graphpinator\Typesystem\Argument\ArgumentSet([
            new \Graphpinator\Typesystem\Argument\Argument('if', \Graphpinator\Typesystem\Container::Boolean()->notNull()),
        ]);
    }
}
