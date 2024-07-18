<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\ScalarType;

#[Description('Boolean built-in type')]
final class BooleanType extends ScalarType
{
    protected const NAME = 'Boolean';

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_bool($rawValue);
    }
}
