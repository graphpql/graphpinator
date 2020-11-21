<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class EmailAddressType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'EmailAddress';
    protected const DESCRIPTION = 'EmailAddress type - string which contains valid email address.';

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue)
            && (bool) \filter_var($rawValue, \FILTER_VALIDATE_EMAIL);
    }
}
