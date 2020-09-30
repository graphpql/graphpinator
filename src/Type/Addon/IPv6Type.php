<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class IPv6Type extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Ipv6';
    protected const DESCRIPTION = 'This add on scalar validates ipv6 string input via filter_var function.
    Examples - aaaa:aaaa:aaaa:aaaa:aaaa:aaaa:aaaa:aaaa, ffff:ffff:ffff:ffff:ffff:ffff:ffff:ffff,
               0000:0000:0000:0000:0000:0000:0000:0000, 9999:9999:9999:9999:9999:9999:9999:9999,
               AAAA:AAAA:AAAA:AAAA:AAAA:AAAA:AAAA:AAAA, FFFF:FFFF:FFFF:FFFF:FFFF:FFFF:FFFF:FFFF';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue) &&
            (bool) \filter_var($rawValue, \FILTER_VALIDATE_IP, \FILTER_FLAG_IPV6);
    }
}
