<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class PhoneNumberType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'PhoneNumber';
    protected const DESCRIPTION = 'This add on scalar validates string phone number input in global prefixes and number counts by countries standards.
    Examples - +420123456789, +99123456789, +9123456789, +99912345678, +9912345678, +912345678';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue) &&
            \preg_match('/(\+{1}[0-9]{1,3}[0-9]{8,9})/', $rawValue) === 1;
    }
}
