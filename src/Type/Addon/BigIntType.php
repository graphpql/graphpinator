<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class BigIntType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'BigInt';
    protected const DESCRIPTION = 'BigInt addon type (' . \PHP_INT_SIZE * 8 . ' bit)';

    public function validateNonNullValue($rawValue) : bool
    {
        return \is_int($rawValue);
    }
}
