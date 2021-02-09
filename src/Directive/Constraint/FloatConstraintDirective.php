<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

final class FloatConstraintDirective extends LeafConstraintDirective
{
    protected const NAME = 'floatConstraint';
    protected const DESCRIPTION = 'Graphpinator floatConstraint directive.';

    public function validateType(
        \Graphpinator\Type\Contract\Definition $definition,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool
    {
        return $definition->getNamedType() instanceof \Graphpinator\Type\Scalar\FloatType;
    }

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            \Graphpinator\Argument\Argument::create('min', \Graphpinator\Container\Container::Float()),
            \Graphpinator\Argument\Argument::create('max', \Graphpinator\Container\Container::Float()),
            \Graphpinator\Argument\Argument::create('oneOf', \Graphpinator\Container\Container::Float()->notNull()->list()),
        ]);
    }

    protected function appendDirectives(): void
    {
        $this->arguments['oneOf']->addDirective(
            $this->constraintDirectiveAccessor->getList(),
            ['minItems' => 1],
        );
    }

    protected function specificValidateValue(
        \Graphpinator\Value\Value $value,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
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

    protected function specificValidateVariance(
        \Graphpinator\Value\ArgumentValueSet $biggerSet,
        \Graphpinator\Value\ArgumentValueSet $smallerSet,
    ) : void
    {
        $lhs = $biggerSet->getRawValues();
        $rhs = $smallerSet->getRawValues();

        if (\is_float($lhs->min) && ($rhs->min === null || $rhs->min < $lhs->min)) {
            throw new \Exception();
        }

        if (\is_float($lhs->max) && ($rhs->max === null || $rhs->max > $lhs->max)) {
            throw new \Exception();
        }

        if (\is_array($lhs->oneOf) && ($rhs->oneOf === null || self::validateOneOf($lhs->oneOf, $rhs->oneOf))) {
            throw new \Exception();
        }
    }
}
