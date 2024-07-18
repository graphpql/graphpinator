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

    public function getRawValue() : ?bool
    {
        return null;
    }

    public function getType() : Outputable
    {
        return $this->type;
    }

    public function jsonSerialize() : ?bool
    {
        return null;
    }
}
