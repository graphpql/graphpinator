<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver\Value;

final class LeafValue extends \Graphpinator\Resolver\Value\ValidatedValue
{
    public function __construct($value, \Graphpinator\Type\Contract\LeafDefinition $type)
    {
        $type->validateValue($value);
        parent::__construct($value, $type);
    }

    public function printValue(bool $prettyPrint) : string
    {
        return \json_encode($this->value, \JSON_THROW_ON_ERROR);
    }
}
