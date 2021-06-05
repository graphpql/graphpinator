<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

final class BooleanType extends \Graphpinator\Typesystem\ScalarType
{
    protected const NAME = 'Boolean';
    protected const DESCRIPTION = 'Boolean built-in type';

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_bool($rawValue);
    }
}
