<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class MacType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Mac';
    protected const DESCRIPTION = 'Mac type - string which contains valid MAC (media access control) address.';

    protected function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue)
            && (bool) \filter_var($rawValue, \FILTER_VALIDATE_MAC);
    }
}
