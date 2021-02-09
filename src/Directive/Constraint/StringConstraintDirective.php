<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

final class StringConstraintDirective extends LeafConstraintDirective
{
    protected const NAME = 'stringConstraint';
    protected const DESCRIPTION = 'Graphpinator stringConstraint directive.';

    public function validateType(
        \Graphpinator\Type\Contract\Definition $definition,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool
    {
        $namedType = $definition->getNamedType();

        return $namedType instanceof \Graphpinator\Type\Scalar\StringType
            || $namedType instanceof \Graphpinator\Type\Scalar\IdType;
    }

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            \Graphpinator\Argument\Argument::create('minLength', \Graphpinator\Container\Container::Int()),
            \Graphpinator\Argument\Argument::create('maxLength', \Graphpinator\Container\Container::Int()),
            \Graphpinator\Argument\Argument::create('regex', \Graphpinator\Container\Container::String()),
            \Graphpinator\Argument\Argument::create('oneOf', \Graphpinator\Container\Container::String()->notNull()->list()),
        ]);
    }

    protected function appendDirectives(): void
    {
        $this->arguments['minLength']->addDirective(
            $this->constraintDirectiveAccessor->getInt(),
            ['min' => 0],
        );
        $this->arguments['maxLength']->addDirective(
            $this->constraintDirectiveAccessor->getInt(),
            ['min' => 0],
        );
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
        $minLength = $arguments->offsetGet('minLength')->getValue()->getRawValue();
        $maxLength = $arguments->offsetGet('maxLength')->getValue()->getRawValue();
        $regex = $arguments->offsetGet('regex')->getValue()->getRawValue();
        $oneOf = $arguments->offsetGet('oneOf')->getValue()->getRawValue();

        if (\is_int($minLength) && \mb_strlen($rawValue) < $minLength) {
            throw new \Graphpinator\Exception\Constraint\MinLengthConstraintNotSatisfied();
        }

        if (\is_int($maxLength) && \mb_strlen($rawValue) > $maxLength) {
            throw new \Graphpinator\Exception\Constraint\MaxLengthConstraintNotSatisfied();
        }

        if (\is_string($regex) && \preg_match($regex, $rawValue) !== 1) {
            throw new \Graphpinator\Exception\Constraint\RegexConstraintNotSatisfied();
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

        if (\is_int($lhs->minLength) && ($rhs->minLength === null || $rhs->minLength < $lhs->minLength)) {
            throw new \Exception();
        }

        if (\is_int($lhs->maxLength) && ($rhs->maxLength === null || $rhs->maxLength > $lhs->maxLength)) {
            throw new \Exception();
        }

        if (\is_string($lhs->regex) && ($rhs->regex === null || $rhs->regex !== $lhs->regex)) {
            throw new \Exception();
        }

        if (\is_array($lhs->oneOf) && ($rhs->oneOf !== null || self::validateOneOf($lhs->oneOf, $rhs->oneOf))) {
            throw new \Exception();
        }
    }
}
