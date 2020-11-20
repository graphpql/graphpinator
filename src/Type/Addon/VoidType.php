<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class VoidType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Void';
    protected const DESCRIPTION = 'Void type - accepts null only.';

    protected function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue === null;
    }
}
