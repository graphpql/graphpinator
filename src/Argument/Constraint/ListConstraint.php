<?php

declare(strict_types = 1);

namespace Graphpinator\Argument\Constraint;

final class ListConstraint extends Constraint
{
    private ?int $minItems;
    private ?int $maxItems;
    private bool $unique;
    private ?\stdClass $innerList;

    public function __construct(?int $minItems = null, ?int $maxItems = null, bool $unique = false, ?\stdClass $innerList = null)
    {
        $this->minItems = $minItems;
        $this->maxItems = $maxItems;
        $this->unique = $unique;
        $this->innerList = $innerList;
    }

    public function printConstraint() : string
    {
        $options = (object) [
            'minItems' => $this->minItems,
            'maxItems' => $this->maxItems,
            'unique' => $this->unique,
            'innerList' => $this->innerList,
        ];

        return '@listConstraint(' . self::recursivePrintConstraint($options) . ')';
    }

    public function validateType(\Graphpinator\Type\Contract\Inputable $type) : bool
    {
        return $type instanceof \Graphpinator\Type\ListType
            || ($type instanceof \Graphpinator\Type\NotNullType && $type->getInnerType() instanceof \Graphpinator\Type\ListType);
    }

    protected function validateFactoryMethod($inputValue) : void
    {
        \assert(\is_array($inputValue));

        $options = (object) [
            'minItems' => $this->minItems,
            'maxItems' => $this->maxItems,
            'unique' => $this->unique,
            'innerList' => $this->innerList,
        ];

        self::recursiveValidateFactoryMethod($options, $inputValue);
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
            $components[] = '{' . self::recursivePrintConstraint($options->innerList) . '}';
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
                if (!\is_scalar($value)) {
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
}
