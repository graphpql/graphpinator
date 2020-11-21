<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Scalar;

final class FloatType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Float';
    protected const DESCRIPTION = 'Float built-in type';

    public function validateNonNullValue($rawValue) : bool
    {
        return \is_float($rawValue);
    }
}
