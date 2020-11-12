<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class ListConstraint extends \Graphpinator\Constraint\ArgumentFieldConstraint
{
    private ?\stdClass $options;

    public function __construct(?int $minItems = null, ?int $maxItems = null, bool $unique = false, ?\stdClass $innerList = null)
    {
        $this->options = self::getOptions((object) [
            'minItems' => $minItems,
            'maxItems' => $maxItems,
            'unique' => $unique,
            'innerList' => $innerList,
        ]);
    }

    public function print() : string
    {
        return '@listConstraint(' . self::recursivePrintConstraint($this->options) . ')';
    }

    public function validateType(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        return self::recursiveValidateType($this->options, $type);
    }

    protected function validateFactoryMethod($inputValue) : void
    {
        \assert(\is_array($inputValue));

        self::recursiveValidateFactoryMethod($this->options, $inputValue);
    }

    protected function isGreaterSet(
        \Graphpinator\Constraint\ArgumentFieldConstraint $greater,
        \Graphpinator\Constraint\ArgumentFieldConstraint $smaller
    ) : bool
    {
        \assert($greater instanceof self);
        \assert($smaller instanceof self);

        return $this->recursiveValidateConstraints($greater->options, $smaller->options);
    }

    private static function recursivePrintConstraint(\stdClass $options) : string
    {
        $components = [];

        if (\is_int($options->minItems)) {
            $components[] = 'minItems: ' . $options->minItems;
        }

        if (\is_int($options->maxItems)) {
            $components[] = 'maxItems: ' . $options->maxItems;
        }

        if ($options->unique) {
            $components[] = 'unique: true';
        }

        if ($options->innerList instanceof \stdClass) {
            $components[] = 'innerList: {' . self::recursivePrintConstraint($options->innerList) . '}';
        }

         return \implode(', ', $components);
    }

    private static function recursiveValidateType(\stdClass $options, \Graphpinator\Type\Contract\Definition $type) : bool
    {
        $usedType = $type;

        if ($usedType instanceof \Graphpinator\Type\NotNullType) {
            $usedType = $usedType->getInnerType();
        }

        if (!$usedType instanceof \Graphpinator\Type\ListType) {
            return false;
        }

        $usedType = $usedType->getInnerType();

        if ($usedType instanceof \Graphpinator\Type\NotNullType) {
            $usedType = $usedType->getInnerType();
        }

        if ($options->unique && !$usedType instanceof \Graphpinator\Type\Contract\LeafDefinition) {
            throw new \Graphpinator\Exception\Constraint\UniqueConstraintOnlyScalar();
        }

        if ($options->innerList instanceof \stdClass) {
            return self::recursiveValidateType($options->innerList, $usedType);
        }

        return true;
    }

    private static function recursiveValidateFactoryMethod(\stdClass $options, array $value) : void
    {
        if (\is_int($options->minItems) && \count($value) < $options->minItems) {
            throw new \Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied();
        }

        if (\is_int($options->maxItems) && \count($value) > $options->maxItems) {
            throw new \Graphpinator\Exception\Constraint\MaxItemsConstraintNotSatisfied();
        }

        if ($options->unique) {
            $differentValues = [];

            foreach ($value as $innerValue) {
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

        foreach ($value as $innerValue) {
            if ($innerValue === null) {
                continue;
            }

            self::recursiveValidateFactoryMethod($options->innerList, $innerValue);
        }
    }

    private static function getOptions(\stdClass $class) : \stdClass
    {
        if (!\property_exists($class, 'minItems')) {
            $class->minItems = null;
        }

        if (!\property_exists($class, 'maxItems')) {
            $class->maxItems = null;
        }

        if ((\is_int($class->minItems) && $class->minItems < 0) ||
            (\is_int($class->maxItems) && $class->maxItems < 0)) {
            throw new \Graphpinator\Exception\Constraint\NegativeCountParameter();
        }

        if (!\property_exists($class, 'unique')) {
            $class->unique = false;
        }

        $class->innerList = \property_exists($class, 'innerList') && $class->innerList instanceof \stdClass
            ? self::getOptions($class->innerList)
            : null;

        return $class;
    }

    private function recursiveValidateConstraints(\stdClass $greater, \stdClass $smaller) : bool
    {
        if (\is_int($greater->minItems) && ($smaller->minItems === null || $smaller->minItems < $greater->minItems)) {
            return false;
        }

        if (\is_int($greater->maxItems) && ($smaller->maxItems === null || $smaller->maxItems > $greater->maxItems)) {
            return false;
        }

        if ($greater->unique !== $smaller->unique) {
            return false;
        }

        return !($smaller->innerList instanceof \stdClass)
            || ($greater->innerList !== null && self::recursiveValidateConstraints($greater->innerList, $smaller->innerList));
    }
}
