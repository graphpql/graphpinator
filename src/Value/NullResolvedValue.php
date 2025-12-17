<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\Contract\Type;

final class NullResolvedValue implements OutputValue, NullValue
{
    public function __construct(
        private Type $type,
    )
    {
    }

    #[\Override]
    public function getRawValue() : ?bool
    {
        return null;
    }

    #[\Override]
    public function getType() : Type
    {
        return $this->type;
    }

    #[\Override]
    public function jsonSerialize() : ?bool
    {
        return null;
    }
}
