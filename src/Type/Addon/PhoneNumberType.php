<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class PhoneNumberType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'PhoneNumber';
    protected const DESCRIPTION = 'PhoneNumber built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        if (!\is_string($rawValue)) {
            return false;
        }

        return \preg_match('/(\+{1}[0-9]{1,3}[0-9]{8,9})/', $rawValue) === 1;
    }
}
