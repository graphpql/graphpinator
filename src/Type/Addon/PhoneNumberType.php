<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class PhoneNumberType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'PhoneNumber';
    protected const DESCRIPTION = 'PhoneNumber type - string which contains valid phone number.'
        . \PHP_EOL . 'The accepted format is without spaces and other special characters, but the leading plus is required.';

    public function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue)
            && \preg_match('/(\+{1}[0-9]{1,3}[0-9]{8,9})/', $rawValue) === 1;
    }
}
