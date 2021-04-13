<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class ScalarType extends \Graphpinator\Type\Contract\LeafDefinition
{
    final public function accept(\Graphpinator\Typesystem\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitScalar($this);
    }

    public function coerceValue(mixed $rawValue) : mixed
    {
        return $rawValue;
    }
}
