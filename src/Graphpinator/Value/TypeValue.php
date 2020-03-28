<?php

declare(strict_types = 1);

namespace PGQL\Value;

final class TypeValue extends ValidatedValue
{
    public function __construct($resolvedValue, \PGQL\Type\Type $type)
    {
        $type->validateValue($resolvedValue);
        parent::__construct($resolvedValue, $type);
    }
}
