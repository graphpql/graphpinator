<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\ListType;
use Graphpinator\Value\Contract\Value;

final class ListIntermediateValue implements Value
{
    public function __construct(
        private ListType $type,
        private iterable $rawValue,
    )
    {
    }

    #[\Override]
    public function getRawValue(bool $forResolvers = false) : iterable
    {
        return $this->rawValue;
    }

    #[\Override]
    public function getType() : ListType
    {
        return $this->type;
    }
}
