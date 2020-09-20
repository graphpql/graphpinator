<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Constraint;

final class InputConstraint implements \Graphpinator\Constraint\Constraint
{
    use \Nette\SmartObject;

    private ?array $atLeastOne;

    public function __construct(?array $atLeastOne = null)
    {
        if (\is_array($atLeastOne)) {
            foreach ($atLeastOne as $item) {
                if (!\is_string($item)) {
                    throw new \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied();
                }
            }
        }

        $this->atLeastOne = $atLeastOne;
    }

    public function print() : string
    {
        $components = [];

        if (\is_array($this->atLeastOne)) {
            $components[] = \count($this->atLeastOne) === 0
                ? 'atLeastOne: []'
                : 'atLeastOne: ["' . \implode('", "', $this->atLeastOne) . '"]';
        }

        return '@inputConstraint(' . \implode(', ', $components) . ')';
    }

    public function validate($rawValue) : void
    {
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
    }

    public function validateType(\Graphpinator\Type\Contract\Inputable $definition) : bool
    {
        if (!$definition instanceof \Graphpinator\Type\InputType) {
            return false;
        }

        $fields = $definition->getArguments();

        foreach ($this->atLeastOne as $item) {
            if (isset($fields[$item])) {
                return false;
            }
        }

        return true;
    }
}
