<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class FloatConstraint extends \Graphpinator\Constraint\LeafConstraint
{
    private ?float $min;
    private ?float $max;
    private ?array $oneOf;

    public function __construct(?float $min = null, ?float $max = null, ?array $oneOf = null)
    {
        if (\is_array($oneOf)) {
            foreach ($oneOf as $item) {
                if (!\is_float($item)) {
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

        if (\is_float($this->min)) {
            $components[] = 'min: ' . $this->min;
        }

        if (\is_float($this->max)) {
            $components[] = 'max: ' . $this->max;
        }

        if (\is_array($this->oneOf)) {
            $components[] = 'oneOf: [' . \implode(', ', $this->oneOf) . ']';
        }

        return '@floatConstraint(' . \implode(', ', $components) . ')';
    }

    public function validateType(\Graphpinator\Type\Contract\Inputable $type) : bool
    {
        return $type->getNamedType() instanceof \Graphpinator\Type\Scalar\FloatType;
    }

    protected function validateFactoryMethod($inputValue) : void
    {
        \assert(\is_float($inputValue));

        if (\is_float($this->min) && $inputValue < $this->min) {
            throw new \Graphpinator\Exception\Constraint\MinConstraintNotSatisfied();
        }

        if (\is_float($this->max) && $inputValue > $this->max) {
            throw new \Graphpinator\Exception\Constraint\MaxConstraintNotSatisfied();
        }

        if (\is_array($this->oneOf) && !\in_array($inputValue, $this->oneOf, true)) {
            throw new \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied();
        }
    }
}
