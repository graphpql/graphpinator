<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ListResolvedValue extends ListValue implements OutputValue
{
    #[\Override]
    public function jsonSerialize() : array
    {
        return \array_values($this->value);
    }
}
