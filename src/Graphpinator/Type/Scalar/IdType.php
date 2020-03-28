<?php

declare(strict_types = 1);

namespace PGQL\Type\Scalar;

final class IdType extends \PGQL\Type\Scalar\ScalarType
{
    protected const NAME = 'ID';
    protected const DESCRIPTION = 'ID built-in type';

    protected function validateNonNullValue($rawValue) : void
    {
        if (\is_int($rawValue) || \is_string($rawValue)) {
            return;
        }

        throw new \Exception();
    }
}
