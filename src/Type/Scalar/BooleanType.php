<?php

declare(strict_types = 1);

namespace PGQL\Type\Scalar;

final class BooleanType extends \PGQL\Type\Scalar\ScalarType
{
    protected const NAME = 'Boolean';
    protected const DESCRIPTION = 'Boolean built-in type';

    protected function validateNonNullValue($rawValue) : void
    {
        if (\is_bool($rawValue)) {
            return;
        }

        throw new \Exception();
    }
}
