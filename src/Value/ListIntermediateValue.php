<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\ListType;

final class ListIntermediateValue implements ResolvedValue
{
    public function __construct(
        private ListType $type,
        private iterable $rawValue,
    )
    {
    }

    public function getRawValue(bool $forResolvers = false) : iterable
    {
        return $this->rawValue;
    }

    public function getType() : ListType
    {
        return $this->type;
    }
}
