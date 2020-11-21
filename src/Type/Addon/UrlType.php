<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class UrlType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'Url';
    protected const DESCRIPTION = 'Url type - string which contains valid URL (Uniform Resource Locator).';

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue)
            && (bool) \filter_var($rawValue, \FILTER_VALIDATE_URL);
    }
}
