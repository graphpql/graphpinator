<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class ObjectConstraint implements \Graphpinator\Constraint\Constraint
{
    public function validate(\Graphpinator\Value\Value $value) : void
    {
        \assert($value instanceof \Graphpinator\Value\InputValue || $value instanceof \Graphpinator\Value\TypeValue);

        if (\is_array($this->atLeastOne)) {
            $valid = false;

            foreach ($this->atLeastOne as $fieldName) {
                if (isset($value->{$fieldName}) && $value->{$fieldName}->getValue() instanceof \Graphpinator\Value\NullValue) {
                    continue;
                }

                $valid = true;

                break;
            }

            if (!$valid) {
                throw new \Graphpinator\Exception\Constraint\AtLeastOneConstraintNotSatisfied();
            }
        }

        if (!\is_array($this->exactlyOne)) {
            return;
        }

        $count = 0;
        $notRequested = 0;

        foreach ($this->exactlyOne as $fieldName) {
            if (!isset($value->{$fieldName})) {
                ++$notRequested;

                continue;
            }

            if ($value->{$fieldName}->getValue() instanceof \Graphpinator\Value\NullValue) {
                continue;
            }

            ++$count;
        }

        if ($count > 1 || ($count === 0 && $notRequested === 0)) {
            throw new \Graphpinator\Exception\Constraint\ExactlyOneConstraintNotSatisfied();
        }
    }

    public function validateConstraint(\Graphpinator\Constraint\ObjectConstraint $childConstraint) : bool
    {
        return $this->atLeastOne === $childConstraint->atLeastOne
            && $this->exactlyOne === $childConstraint->exactlyOne;
    }
}
