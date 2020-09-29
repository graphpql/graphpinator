<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class MacType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'mac';
    protected const DESCRIPTION = 'This add on scalar validates mac string input via filter_var function.
    Examples - aa:aa:aa:aa:aa:aa, ff:ff:ff:ff:ff:ff, AA:AA:AA:AA:AA:AA, FF:FF:FF:FF:FF:FF, 00:00:00:00:00:00, 99:99:99:99:99:99';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue) &&
            (bool) \filter_var($rawValue, \FILTER_VALIDATE_MAC);
    }
}
