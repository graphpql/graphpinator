<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Spec;

final class IdType extends \Graphpinator\Type\ScalarType
{
    protected const NAME = 'ID';
    protected const DESCRIPTION = 'ID built-in type';

    public function createInputedValue($rawValue) : \Graphpinator\Value\InputedValue
    {
        if (\is_int($rawValue)) {
            $rawValue = (string) $rawValue;
        }

        return parent::createInputedValue($rawValue);
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue);
    }
}
