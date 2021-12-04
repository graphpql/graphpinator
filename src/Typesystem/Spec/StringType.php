<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

#[\Graphpinator\Typesystem\Attribute\Description('String built-in type')]
final class StringType extends \Graphpinator\Typesystem\ScalarType
{
    protected const NAME = 'String';

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue);
    }
}
