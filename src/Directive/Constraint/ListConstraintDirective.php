<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

final class ListConstraintDirective extends FieldConstraintDirective
{
    protected const NAME = 'listConstraint';
    protected const DESCRIPTION = 'Graphpinator listConstraint directive.';

    public function validateType(
        \Graphpinator\Type\Contract\Definition $definition,
        \Graphpinator\Value\ArgumentValueSet $arguments,
    ) : bool
    {
        return self::recursiveValidateType($definition, (object) $arguments->getValuesForResolver());
    }

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return $this->constraintDirectiveAccessor->getListInput()->getArguments();
    }

    protected function validateValue(
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

    protected function specificValidateVariance(
        \Graphpinator\Value\ArgumentValueSet $biggerSet,
        \Graphpinator\Value\ArgumentValueSet $smallerSet,
    ) : void
    {
        self::recursiveSpecificValidateVariance(
            $biggerSet->getRawValues(),
            $smallerSet->getRawValues(),
        );
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

    private static function recursiveSpecificValidateVariance(
        \stdClass $greater,
        \stdClass $smaller,
    ) : void
    {
        if (\is_int($greater->minItems) && ($smaller->minItems === null || $smaller->minItems < $greater->minItems)) {
            throw new \Exception();
        }

        if (\is_int($greater->maxItems) && ($smaller->maxItems === null || $smaller->maxItems > $greater->maxItems)) {
            throw new \Exception();
        }

        if ($greater->unique === true && ($smaller->unique === null || $smaller->unique === false)) {
            throw new \Exception();
        }

        if ($greater->innerList instanceof \stdClass) {
            if ($smaller->innerList === null) {
                throw new \Exception();
            }

            self::recursiveSpecificValidateVariance($greater->innerList, $smaller->innerList);
        }
    }
}
