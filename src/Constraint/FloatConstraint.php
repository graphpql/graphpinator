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

    public function validateType(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        return $type->getNamedType() instanceof \Graphpinator\Type\Scalar\FloatType;
    }

    public function isCovariant(\Graphpinator\Constraint\ArgumentFieldConstraint $childConstraint) : bool
    {
        if (!$childConstraint instanceof self) {
            throw new \Exception('asdf');
        }

        if (\is_float($childConstraint->min) && $this->min < $childConstraint->min
            || $this->min === null && \is_float($childConstraint->min)) {
            return false;
        }

        if (\is_float($childConstraint->max) && $this->max > $childConstraint->max
            || $this->max === null && \is_float($childConstraint->max)) {
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

        if (\is_float($this->min) && $this->min > $childConstraint->min
            || $childConstraint->min === null && \is_float($this->min)) {
            return false;
        }

        if (\is_float($this->max) && $this->max < $childConstraint->max
            || $childConstraint->max === null && \is_float($this->max)) {
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
