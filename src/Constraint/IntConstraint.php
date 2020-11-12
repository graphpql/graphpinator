<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class IntConstraint extends \Graphpinator\Constraint\LeafConstraint
{
    private ?int $min;
    private ?int $max;
    private ?array $oneOf;

    public function __construct(?int $min = null, ?int $max = null, ?array $oneOf = null)
    {
        if (\is_array($oneOf)) {
            foreach ($oneOf as $item) {
                if (!\is_int($item)) {
                    throw new \Graphpinator\Exception\Constraint\InvalidOneOfParameter();
                }
            }
        }

        $this->min = $min;
        $this->max = $max;
        $this->oneOf = $oneOf;
    }

    public function print() : string
    {
        $components = [];

        if (\is_int($this->min)) {
            $components[] = 'min: ' . $this->min;
        }

        if (\is_int($this->max)) {
            $components[] = 'max: ' . $this->max;
        }

        if (\is_array($this->oneOf)) {
            $components[] = 'oneOf: [' . \implode(', ', $this->oneOf) . ']';
        }

        return '@intConstraint(' . \implode(', ', $components) . ')';
    }

    public function validateType(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        return $type->getNamedType() instanceof \Graphpinator\Type\Scalar\IntType;
    }

    protected function validateFactoryMethod($inputValue) : void
    {
        \assert(\is_int($inputValue));

        if (\is_int($this->min) && $inputValue < $this->min) {
            throw new \Graphpinator\Exception\Constraint\MinConstraintNotSatisfied();
        }

        if (\is_int($this->max) && $inputValue > $this->max) {
            throw new \Graphpinator\Exception\Constraint\MaxConstraintNotSatisfied();
        }

        if (\is_array($this->oneOf) && !\in_array($inputValue, $this->oneOf, true)) {
            throw new \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied();
        }
    }

    protected function isGreaterSet(
        \Graphpinator\Constraint\ArgumentFieldConstraint $greater,
        \Graphpinator\Constraint\ArgumentFieldConstraint $smaller
    ) : bool
    {
        \assert($greater instanceof self);
        \assert($smaller instanceof self);

        if (\is_int($greater->min) && ($smaller->min === null || $smaller->min < $greater->min)) {
            return false;
        }

        if (\is_int($greater->max) && ($smaller->max === null || $smaller->max > $greater->max)) {
            return false;
        }

        return !\is_array($greater->oneOf) || ($smaller->oneOf !== null && self::validateOneOf($greater->oneOf, $smaller->oneOf));
    }
}
