<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Scalar;

final class BooleanType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Boolean';
    protected const DESCRIPTION = 'Boolean built-in type';

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_bool($rawValue);
    }
}
