<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ListIntermediateValue implements \Graphpinator\Value\ResolvedValue
{
    public function __construct(
        private \Graphpinator\Typesystem\ListType $type,
        private iterable $rawValue,
    )
    {
    }

    public function getRawValue(bool $forResolvers = false) : iterable
    {
        return $this->rawValue;
    }

    public function getType() : \Graphpinator\Typesystem\ListType
    {
        return $this->type;
    }
}
