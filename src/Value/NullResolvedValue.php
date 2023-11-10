<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class NullResolvedValue implements \Graphpinator\Value\OutputValue, \Graphpinator\Value\NullValue
{
    public function __construct(
        private \Graphpinator\Typesystem\Contract\Outputable $type,
    )
    {
    }

    public function getRawValue() : ?bool
    {
        return null;
    }

    public function getType() : \Graphpinator\Typesystem\Contract\Outputable
    {
        return $this->type;
    }

    public function jsonSerialize() : ?bool
    {
        return null;
    }
}
