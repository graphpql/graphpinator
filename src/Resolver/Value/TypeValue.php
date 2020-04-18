<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver\Value;

final class TypeValue extends ValidatedValue
{
    public function __construct($resolvedValue, \Graphpinator\Type\Type $type)
    {
        $type->validateValue($resolvedValue);
        parent::__construct($resolvedValue, $type);
    }
}
