<?php

declare(strict_types = 1);

namespace PGQL\Value;

final class ScalarValue extends \PGQL\Value\ValidatedValue
{
    public function __construct($value, \PGQL\Type\Scalar\ScalarType $type)
    {
        $type->validateValue($value);
        parent::__construct($value, $type);
    }
}
