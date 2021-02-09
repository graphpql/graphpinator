<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

abstract class LeafConstraintDirective extends FieldConstraintDirective
{
    final protected function validateValue(
        \Graphpinator\Value\Value $value,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        if ($value instanceof \Graphpinator\Value\NullValue) {
            return;
        }

        if ($value instanceof \Graphpinator\Value\ListValue) {
            foreach ($value as $item) {
                $this->validateValue($item, $arguments);
            }

            return;
        }

        $this->specificValidateValue($value, $arguments);
    }

    abstract protected function specificValidateValue(
        \Graphpinator\Value\Value $value,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void;

    protected static function validateOneOf(array $greater, array $smaller) : bool
    {
        foreach ($smaller as $value) {
            if (!\in_array($value, $greater, true)) {
                return false;
            }
        }

        return true;
    }
}
