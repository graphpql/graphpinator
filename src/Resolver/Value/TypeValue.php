<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver\Value;

final class TypeValue extends \Graphpinator\Resolver\Value\ValidatedValue
{
    public function __construct($resolvedValue, \Graphpinator\Type\Type $type)
    {
        $type->validateValue($resolvedValue);
        parent::__construct($resolvedValue, $type);
    }

    public function printValue() : string
    {
        throw new \Graphpinator\Exception\Resolver\UnprintableValue();
    }
}
