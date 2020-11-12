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

    public function isCovariant(\Graphpinator\Constraint\ArgumentFieldConstraint $childConstraint) : bool
    {
        if (!$childConstraint instanceof self) {
            throw new \Exception('asdf');
        }

        if (\is_int($childConstraint->min) && $this->min < $childConstraint->min
            || $this->min === null && \is_int($childConstraint->min)) {
            return false;
        }

        if (\is_int($childConstraint->max) && $this->max > $childConstraint->max
            || $this->max === null && \is_int($childConstraint->max)) {
            return false;
        }

        if (\is_array($this->oneOf) && \is_array($childConstraint->oneOf)) {
            foreach ($this->oneOf as $value) {
                if (!\in_array($value, $childConstraint->oneOf, true)) {
                    return false;
                }
            }
        } elseif ($this->oneOf === null && $childConstraint->oneOf !== null) {
            return false;
        }

        return true;
    }

    public function isContravariant(\Graphpinator\Constraint\ArgumentFieldConstraint $childConstraint) : bool
    {
        if (!$childConstraint instanceof self) {
            throw new \Exception('asdf');
        }

        if (\is_int($this->min) && $this->min > $childConstraint->min
            || $childConstraint->min === null && \is_int($this->min)) {
            return false;
        }

        if (\is_int($this->max) && $this->max < $childConstraint->max
            || $childConstraint->max === null && \is_int($this->max)) {
            return false;
        }

        if (\is_array($this->oneOf) && \is_array($childConstraint->oneOf)) {
            foreach ($childConstraint->oneOf as $value) {
                if (!\in_array($value, $this->oneOf)) {
                    return false;
                }
            }
        } elseif ($this->oneOf !== null && $childConstraint->oneOf === null) {
            return false;
        }

        return true;
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
}
