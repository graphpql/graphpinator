<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Spec;

final class IncludeDirective extends \Graphpinator\Directive\Directive
    implements \Graphpinator\Directive\Contract\FieldLocation
{
    protected const NAME = 'include';
    protected const DESCRIPTION = 'Built-in include directive.';

    public function validateType(
        ?\Graphpinator\Type\Contract\Definition $definition,
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
            ? \Graphpinator\Directive\FieldDirectiveResult::NONE
            : \Graphpinator\Directive\FieldDirectiveResult::SKIP;
    }

    public function resolveFieldAfter(
        \Graphpinator\Value\FieldValue $fieldValue,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : string
    {
        return \Graphpinator\Directive\FieldDirectiveResult::NONE;
    }

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            new \Graphpinator\Argument\Argument('if', \Graphpinator\Container\Container::Boolean()->notNull()),
        ]);
    }
}
