<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Addon;

final class UrlType extends \Graphpinator\Type\Scalar\ScalarType
{
    protected const NAME = 'URL';
    protected const DESCRIPTION = 'URL built-in type';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $rawValue) === 1;
    }
}
