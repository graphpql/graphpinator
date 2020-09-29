<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class UrlType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'URL';
    protected const DESCRIPTION = 'URL built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        if (!\is_string($rawValue)) {
            return false;
        }

        return (bool) \filter_var($rawValue, \FILTER_VALIDATE_URL);
    }
}
