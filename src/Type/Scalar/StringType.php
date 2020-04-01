<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Scalar;

final class StringType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'String';
    protected const DESCRIPTION = 'String built-in type';

    protected function validateNonNullValue($rawValue) : void
    {
        if (\is_string($rawValue)) {
            return;
        }

        throw new \Exception();
    }
}
