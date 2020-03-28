<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Value;

final class ScalarValue extends \Infinityloop\Graphpinator\Value\ValidatedValue
{
    public function __construct($value, \Infinityloop\Graphpinator\Type\Scalar\ScalarType $type)
    {
        $type->validateValue($value);
        parent::__construct($value, $type);
    }
}
