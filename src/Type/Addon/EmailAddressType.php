<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class EmailAddressType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'EmailAddress';
    protected const DESCRIPTION = 'This add on scalar validates email address string input via filter_var function.
    Examples - test@test.com, test@test.eu, test@test.cz';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue)
            && (bool) \filter_var($rawValue, \FILTER_VALIDATE_EMAIL);
    }
}
