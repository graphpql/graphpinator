<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver\Value;

final class ScalarValue extends \Graphpinator\Resolver\Value\ValidatedValue
{
    public function __construct($value, \Graphpinator\Type\Scalar\ScalarType $type)
    {
        $type->validateValue($value);
        parent::__construct($value, $type);
    }
}
