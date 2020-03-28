<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Value;

final class TypeValue extends ValidatedValue
{
    public function __construct($resolvedValue, \Infinityloop\Graphpinator\Type\Type $type)
    {
        $type->validateValue($resolvedValue);
        parent::__construct($resolvedValue, $type);
    }
}
