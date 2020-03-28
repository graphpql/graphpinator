<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Scalar;

final class IntType extends \Infinityloop\Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Int';
    protected const DESCRIPTION = 'Int built-in type';

    protected function validateNonNullValue($rawValue) : void
    {
        if (\is_int($rawValue)) {
            return;
        }

        throw new \Exception();
    }
}
