<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class PostalCodeType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'PostalCode';
    protected const DESCRIPTION = 'This add on scalar validates string postal code input and its format.
    Examples - 111 11, 222 22, 123 45';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue) &&
            \preg_match('/^[0-9]{3}\s[0-9]{2}$/', $rawValue) === 1;
    }
}
