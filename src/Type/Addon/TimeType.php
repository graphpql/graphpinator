<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class TimeType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Time';
    protected const DESCRIPTION = 'Time built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \preg_match('/^(?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/', $rawValue) === 1;
    }
}
