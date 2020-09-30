<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class IPv4Type extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Ipv4';
    protected const DESCRIPTION = 'This add on scalar validates ipv4 string input via filter_var function.
    Examples - 0.0.0.0, 255.255.255.255, 128.0.1.1';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue) &&
            (bool) \filter_var($rawValue, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV4);
    }
}
