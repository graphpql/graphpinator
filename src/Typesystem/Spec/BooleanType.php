<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

#[\Graphpinator\Typesystem\Attribute\Description('Boolean built-in type')]
final class BooleanType extends \Graphpinator\Typesystem\ScalarType
{
    protected const NAME = 'Boolean';

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_bool($rawValue);
    }
}
