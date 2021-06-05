<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

final class StringType extends \Graphpinator\Typesystem\ScalarType
{
    protected const NAME = 'String';
    protected const DESCRIPTION = 'String built-in type';

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue);
    }
}
