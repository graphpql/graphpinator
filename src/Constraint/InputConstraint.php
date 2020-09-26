<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class InputConstraint implements \Graphpinator\Constraint\Constraint
{
    use \Nette\SmartObject;

    private ?array $atLeastOne;
    private ?array $exactlyOne;

    public function __construct(?array $atLeastOne = null, ?array $exactlyOne = null)
    {
        if (\is_array($atLeastOne)) {
            if (\count($atLeastOne) === 0) {
                throw new \Graphpinator\Exception\Constraint\InvalidAtLeastOneParameter();
            }

            foreach ($atLeastOne as $item) {
                if (!\is_string($item)) {
                    throw new \Graphpinator\Exception\Constraint\InvalidAtLeastOneParameter();
                }
            }
        }

        if (\is_array($exactlyOne)) {
            if (\count($exactlyOne) === 0) {
                throw new \Graphpinator\Exception\Constraint\InvalidExactlyOneParameter();
            }

            foreach ($exactlyOne as $item) {
                if (!\is_string($item)) {
                    throw new \Graphpinator\Exception\Constraint\InvalidExactlyOneParameter();
                }
            }
        }

        $this->atLeastOne = $atLeastOne;
        $this->exactlyOne = $exactlyOne;
    }

    public function print() : string
    {
        $components = [];

        if (\is_array($this->atLeastOne)) {
            $components[] = 'atLeastOne: ["' . \implode('", "', $this->atLeastOne) . '"]';
        }

        if (\is_array($this->exactlyOne)) {
            $components[] = 'exactlyOne: ["' . \implode('", "', $this->exactlyOne) . '"]';
        }

        return '@inputConstraint(' . \implode(', ', $components) . ')';
    }

    public function validate(\Graphpinator\Resolver\Value\ValidatedValue $value) : void
    {
        $rawValue = $value->getRawValue();
        \assert($rawValue instanceof \stdClass);

        if (\is_array($this->atLeastOne)) {
            $valid = false;

            foreach ($this->atLeastOne as $item) {
                if (isset($rawValue->{$item}) && $rawValue->{$item} !== null) {
                    $valid = true;

                    break;
                }
            }

            if (!$valid) {
                throw new \Graphpinator\Exception\Constraint\AtLeastOneConstraintNotSatisfied();
            }
        }

        if (!\is_array($this->exactlyOne)) {
            return;
        }

        $count = 0;

        foreach ($this->exactlyOne as $item) {
            if (isset($rawValue->{$item}) && $rawValue->{$item} !== null) {
                ++$count;
            }
        }

        if ($count !== 1) {
            throw new \Graphpinator\Exception\Constraint\ExactlyOneConstraintNotSatisfied();
        }
    }

    public function validateType(\Graphpinator\Type\Contract\Inputable $definition) : bool
    {
        if (!$definition instanceof \Graphpinator\Type\InputType) {
            return false;
        }

        $fields = $definition->getArguments();

        if (\is_array($this->atLeastOne)) {
            foreach ($this->atLeastOne as $item) {
                if (!isset($fields[$item])) {
                    return false;
                }
            }
        }

        if (\is_array($this->exactlyOne)) {
            foreach ($this->exactlyOne as $item) {
                if (!isset($fields[$item])) {
                    return false;
                }
            }
        }

        return true;
    }
}
