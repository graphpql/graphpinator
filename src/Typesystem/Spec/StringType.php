<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\ScalarType;

#[Description('String built-in type')]
final class StringType extends ScalarType
{
    protected const NAME = 'String';

    #[\Override]
    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue);
    }
}
