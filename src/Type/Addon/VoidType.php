<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class VoidType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Void';
    protected const DESCRIPTION = 'This add on scalar validates void input.
    Example - null';

    protected function validateNonNullValue($rawValue) : bool
    {
        return $rawValue === null;
    }
}
