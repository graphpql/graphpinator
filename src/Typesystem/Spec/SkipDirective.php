<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use \Graphpinator\Typesystem\Location\FieldLocation;
use \Graphpinator\Typesystem\Location\FragmentSpreadLocation;
use \Graphpinator\Typesystem\Location\InlineFragmentLocation;
use \Graphpinator\Value\ArgumentValueSet;

final class SkipDirective extends \Graphpinator\Typesystem\Directive implements
    FieldLocation,
    InlineFragmentLocation,
    FragmentSpreadLocation
{
    protected const NAME = 'skip';
    protected const DESCRIPTION = 'Built-in skip directive.';

    public function validateFieldUsage(
        \Graphpinator\Typesystem\Field\Field $field,
        ArgumentValueSet $arguments,
    ) : bool
    {
        return true;
    }

    public function resolveFieldBefore(
        ArgumentValueSet $arguments,
    ) : string
    {
        return $arguments->offsetGet('if')->getValue()->getRawValue()
            ? FieldLocation::SKIP
            : FieldLocation::NONE;
    }

    public function resolveFieldAfter(
        ArgumentValueSet $arguments,
        \Graphpinator\Value\FieldValue $fieldValue,
    ) : string
    {
        return FieldLocation::NONE;
    }

    public function resolveFragmentSpreadBefore(
        ArgumentValueSet $arguments,
    ) : string
    {
        return $this->resolveFieldBefore($arguments);
    }

    public function resolveFragmentSpreadAfter(
        ArgumentValueSet $arguments,
    ) : void
    {
        // nothing here
    }

    public function resolveInlineFragmentBefore(
        ArgumentValueSet $arguments,
    ) : string
    {
        return $this->resolveFieldBefore($arguments);
    }

    public function resolveInlineFragmentAfter(
        ArgumentValueSet $arguments,
    ) : void
    {
        // nothing here
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
    {
        return new \Graphpinator\Typesystem\Argument\ArgumentSet([
            new \Graphpinator\Typesystem\Argument\Argument('if', \Graphpinator\Typesystem\Container::Boolean()->notNull()),
        ]);
    }
}
