<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\Contract\Outputable;

final class NullResolvedValue implements OutputValue, NullValue
{
    public function __construct(
        private Outputable $type,
    )
    {
    }

    #[\Override]
    public function getRawValue() : ?bool
    {
        return null;
    }

    #[\Override]
    public function getType() : Outputable
    {
        return $this->type;
    }

    #[\Override]
    public function jsonSerialize() : ?bool
    {
        return null;
    }
}
