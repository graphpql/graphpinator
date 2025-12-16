<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\ListType;

final class ListResolvedValue extends ListValue implements OutputValue
{
    #[\Override]
    public function getRawValue() : array
    {
        $return = [];

        foreach ($this->value as $listItem) {
            \assert($listItem instanceof ResolvedValue);

            $return[] = $listItem->getRawValue();
        }

        return $return;
    }

    #[\Override]
    public function getType() : ListType
    {
        return $this->type;
    }

    #[\Override]
    public function jsonSerialize() : array
    {
        return \array_values($this->value);
    }
}
