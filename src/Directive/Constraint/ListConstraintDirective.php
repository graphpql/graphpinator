<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

final class ListConstraintDirective extends LeafConstraintDirective
{
    protected const NAME = 'listConstraint';
    protected const DESCRIPTION = 'Graphpinator listConstraint directive.';

    public function validateType(
        ?\Graphpinator\Type\Contract\Definition $definition,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool
    {
        return $definition instanceof \Graphpinator\Type\Contract\Definition
            ? self::recursiveValidateType($definition, (object) $arguments->getValuesForResolver())
            : false;
    }

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return \Graphpinator\Container\Container::listConstraintInput()->getArguments();
    }

    protected function validate(
        \Graphpinator\Value\Value $value,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : void
    {
        if ($value instanceof \Graphpinator\Value\NullValue) {
            return;
        }

        \assert($value instanceof \Graphpinator\Value\ListValue);

        self::recursiveValidate($value->getRawValue(), (object) $arguments->getValuesForResolver());
    }

    private static function recursiveValidateType(
        \Graphpinator\Type\Contract\Definition $type,
        \stdClass $options,
    ) : bool
    {
        $usedType = $type->getShapingType();

        if (!$usedType instanceof \Graphpinator\Type\ListType) {
            return false;
        }

        $usedType = $usedType->getInnerType()->getShapingType();

        if ($options->unique && !$usedType instanceof \Graphpinator\Type\Contract\LeafDefinition) {
            throw new \Graphpinator\Exception\Constraint\UniqueConstraintOnlyScalar();
        }

        if ($options->innerList instanceof \stdClass) {
            return self::recursiveValidateType($usedType, $options->innerList);
        }

        return true;
    }

    private static function recursiveValidate(array $rawValue, \stdClass $options) : void
    {
        if (\is_int($options->minItems) && \count($rawValue) < $options->minItems) {
            throw new \Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied();
        }

        if (\is_int($options->maxItems) && \count($rawValue) > $options->maxItems) {
            throw new \Graphpinator\Exception\Constraint\MaxItemsConstraintNotSatisfied();
        }

        if ($options->unique) {
            $differentValues = [];

            foreach ($rawValue as $innerValue) {
                if (!\array_key_exists($innerValue, $differentValues)) {
                    $differentValues[$innerValue] = true;

                    continue;
                }

                throw new \Graphpinator\Exception\Constraint\UniqueConstraintNotSatisfied();
            }
        }

        if (!$options->innerList instanceof \stdClass) {
            return;
        }

        foreach ($rawValue as $innerValue) {
            if ($innerValue === null) {
                continue;
            }

            self::recursiveValidate($innerValue, $options->innerList);
        }
    }
}
