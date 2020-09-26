<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class MacType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'MAC';
    protected const DESCRIPTION = 'MAC built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        return (bool) \filter_var($rawValue, \FILTER_VALIDATE_MAC);
    }
}
