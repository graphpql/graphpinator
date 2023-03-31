<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use \Graphpinator\Typesystem\Location\SelectionDirectiveResult;
use \Graphpinator\Value\ArgumentValueSet;

#[\Graphpinator\Typesystem\Attribute\Description('Built-in skip directive')]
final class SkipDirective extends \Graphpinator\Typesystem\Directive implements
    \Graphpinator\Typesystem\Location\FieldLocation,
    \Graphpinator\Typesystem\Location\InlineFragmentLocation,
    \Graphpinator\Typesystem\Location\FragmentSpreadLocation
{
    protected const NAME = 'skip';

    public function validateFieldUsage(\Graphpinator\Typesystem\Field\Field $field, ArgumentValueSet $arguments) : bool
    {
        return true;
    }

    public function resolveFieldBefore(ArgumentValueSet $arguments) : SelectionDirectiveResult
    {
        return $arguments->offsetGet('if')->getValue()->getRawValue()
            ? SelectionDirectiveResult::SKIP
            : SelectionDirectiveResult::NONE;
    }

    public function resolveFieldAfter(ArgumentValueSet $arguments, \Graphpinator\Value\FieldValue $fieldValue) : SelectionDirectiveResult
    {
        return SelectionDirectiveResult::NONE;
    }

    public function resolveFragmentSpreadBefore(ArgumentValueSet $arguments) : SelectionDirectiveResult
    {
        return $this->resolveFieldBefore($arguments);
    }

    public function resolveFragmentSpreadAfter(ArgumentValueSet $arguments) : void
    {
        // nothing here
    }

    public function resolveInlineFragmentBefore(ArgumentValueSet $arguments) : SelectionDirectiveResult
    {
        return $this->resolveFieldBefore($arguments);
    }

    public function resolveInlineFragmentAfter(ArgumentValueSet $arguments) : void
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
