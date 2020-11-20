<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class IPv6Type extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Ipv6';
    protected const DESCRIPTION = 'Ipv6 type - string which contains valid IPv6 address.';

    protected function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue)
            && (bool) \filter_var($rawValue, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6);
    }
}
