<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class IPv6Type extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'IPv6';
    protected const DESCRIPTION = 'IPv6 built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \filter_var($rawValue, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6)
            ?? true;
    }
}
