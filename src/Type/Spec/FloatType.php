<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Spec;

final class FloatType extends \Graphpinator\Type\ScalarType
{
    protected const NAME = 'Float';
    protected const DESCRIPTION = 'Float built-in type';

    public function createInputedValue($rawValue) : \Graphpinator\Value\InputedValue
    {
        if (\is_int($rawValue)) {
            $rawValue = (float) $rawValue;
        }

        return parent::createInputedValue($rawValue);
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_float($rawValue);
    }
}
