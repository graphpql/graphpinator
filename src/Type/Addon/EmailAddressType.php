<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class EmailAddressType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'EmailAddress';
    protected const DESCRIPTION = 'EmailAddress built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \filter_var($rawValue, \FILTER_VALIDATE_EMAIL)
            ?? true;
    }
}
