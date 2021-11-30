<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_CLASS_CONSTANT)]
final class Description
{
    public function __construct(
        private string $value,
    )
    {
    }

    public function getValue() : string
    {
        return $this->value;
    }
}
