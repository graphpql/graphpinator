<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Directive;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Typesystem\Location\FieldLocation;
use Graphpinator\Typesystem\Location\FragmentSpreadLocation;
use Graphpinator\Typesystem\Location\InlineFragmentLocation;
use Graphpinator\Typesystem\Location\SelectionDirectiveResult;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\FieldValue;

#[Description('Built-in include directive')]
final class IncludeDirective extends Directive implements
    FieldLocation,
    InlineFragmentLocation,
    FragmentSpreadLocation
{
    protected const NAME = 'include';

    #[\Override]
    public function validateFieldUsage(Field $field, ArgumentValueSet $arguments) : bool
    {
        return true;
    }

    #[\Override]
    public function resolveFieldBefore(ArgumentValueSet $arguments) : SelectionDirectiveResult
    {
        return $arguments->offsetGet('if')->getValue()->getRawValue() === true
            ? SelectionDirectiveResult::NONE
            : SelectionDirectiveResult::SKIP;
    }

    #[\Override]
    public function resolveFieldAfter(ArgumentValueSet $arguments, FieldValue $fieldValue) : SelectionDirectiveResult
    {
        return SelectionDirectiveResult::NONE;
    }

    #[\Override]
    public function resolveFragmentSpreadBefore(ArgumentValueSet $arguments) : SelectionDirectiveResult
    {
        return $this->resolveFieldBefore($arguments);
    }

    #[\Override]
    public function resolveFragmentSpreadAfter(ArgumentValueSet $arguments) : void
    {
        // nothing here
    }

    #[\Override]
    public function resolveInlineFragmentBefore(ArgumentValueSet $arguments) : SelectionDirectiveResult
    {
        return $this->resolveFieldBefore($arguments);
    }

    #[\Override]
    public function resolveInlineFragmentAfter(ArgumentValueSet $arguments) : void
    {
        // nothing here
    }

    #[\Override]
    protected function getFieldDefinition() : ArgumentSet
    {
        return new ArgumentSet([
            new Argument('if', Container::Boolean()->notNull()),
        ]);
    }
}
