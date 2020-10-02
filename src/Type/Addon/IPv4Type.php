<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class IPv4Type extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Ipv4';
    protected const DESCRIPTION = 'Ipv4 type - string which contains valid IPv4 address.';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue)
            && (bool) \filter_var($rawValue, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4);
    }
}
