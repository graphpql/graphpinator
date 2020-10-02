<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class PostalCodeType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'PostalCode';
    protected const DESCRIPTION = 'PostalCode type - string which contains valid postal code (ZIP code) in "NNN NN" format.';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue)
            && \preg_match('/^[0-9]{3}\s[0-9]{2}$/', $rawValue) === 1;
    }
}
