<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Spec;

final class BooleanType extends \Graphpinator\Type\ScalarType
{
    protected const NAME = 'Boolean';
    protected const DESCRIPTION = 'Boolean built-in type';

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_bool($rawValue);
    }
}
