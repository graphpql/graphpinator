<?php

declare(strict_types = 1);

namespace Graphpinator\Argument\Constraint;

final class ListConstraint extends Constraint
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

    public function printConstraint() : string
    {
        return '@listConstraint(' . self::recursivePrintConstraint($this->options) . ')';
    }

    public function validateType(\Graphpinator\Type\Contract\Inputable $type) : bool
    {
        return $type instanceof \Graphpinator\Type\ListType
            || ($type instanceof \Graphpinator\Type\NotNullType && $type->getInnerType() instanceof \Graphpinator\Type\ListType);
    }

    protected function validateFactoryMethod($inputValue) : void
    {
        \assert(\is_array($inputValue));

        self::recursiveValidateFactoryMethod($this->options, $inputValue);
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
                if (!\is_scalar($innerValue)) {
                    throw new \Graphpinator\Exception\Constraint\UniqueConstraintOnlyScalar();
                }

                if (!\array_key_exists($innerValue, $differentValues)) {
                    $differentValues[$innerValue] = true;

                    continue;
                }

                throw new \Graphpinator\Exception\Constraint\UniqueConstraintNotSatisfied();
            }
        }

        if ($options->innerList instanceof \stdClass) {
            foreach ($value as $innerValue) {
                if ($innerValue === null) {
                    continue;
                }

                if (!\is_array($innerValue)) {
                    throw new \Graphpinator\Exception\Constraint\InnerListNotList();
                }

                self::recursiveValidateFactoryMethod($options->innerList, $innerValue);
            }
        }
    }

    private static function getOptions(\stdClass $class) : \stdClass
    {
        if (!isset($class->minItems)) {
            $class->minItems = null;
        }

        if (!isset($class->maxItems)) {
            $class->maxItems = null;
        }

        if ((\is_int($class->minItems) && $class->minItems < 0) ||
            (\is_int($class->maxItems) && $class->maxItems < 0)) {
            throw new \Graphpinator\Exception\Constraint\NegativeCountParameter();
        }

        if (!isset($class->unique)) {
            $class->unique = false;
        }

        $class->innerList = isset($class->innerList)
            ? self::getOptions($class->innerList)
            : null;

        return $class;
    }
}
