<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

final class IntConstraintDirective extends LeafConstraintDirective
{
    protected const NAME = 'intConstraint';
    protected const DESCRIPTION = 'Graphpinator intConstraint directive.';

    public function validateType(
        ?\Graphpinator\Type\Contract\Definition $definition,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool
    {
        return $definition?->getNamedType() instanceof \Graphpinator\Type\Scalar\IntType;
    }

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            \Graphpinator\Argument\Argument::create('min', \Graphpinator\Container\Container::Int()),
            \Graphpinator\Argument\Argument::create('max', \Graphpinator\Container\Container::Int()),
            \Graphpinator\Argument\Argument::create('oneOf', \Graphpinator\Container\Container::Int()->notNull()->list())
                ->addDirective(
                    \Graphpinator\Container\Container::directiveListConstraint(),
                    ['minCount' => 1],
                ),
        ]);
    }

    protected function validate(
        \Graphpinator\Value\Value $value,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        if ($value instanceof \Graphpinator\Value\NullValue) {
            return;
        }

        if ($value instanceof \Graphpinator\Value\ListValue) {
            foreach ($value as $item) {
                $this->validate($item, $arguments);
            }

            return;
        }

        $rawValue = $value->getRawValue();
        $min = $arguments->offsetGet('min')->getValue()->getRawValue();
        $max = $arguments->offsetGet('max')->getValue()->getRawValue();
        $oneOf = $arguments->offsetGet('oneOf')->getValue()->getRawValue();

        if (\is_float($min) && $rawValue < $min) {
            throw new \Graphpinator\Exception\Constraint\MinConstraintNotSatisfied();
        }

        if (\is_float($max) && $rawValue > $max) {
            throw new \Graphpinator\Exception\Constraint\MaxConstraintNotSatisfied();
        }

        if (\is_array($oneOf) && !\in_array($rawValue, $oneOf, true)) {
            throw new \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied();
        }
    }
}
