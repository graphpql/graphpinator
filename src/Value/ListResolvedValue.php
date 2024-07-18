<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\ListType;

final class ListResolvedValue extends ListValue implements OutputValue
{
    public function getRawValue() : array
    {
        $return = [];

        foreach ($this->value as $listItem) {
            \assert($listItem instanceof ResolvedValue);

            $return[] = $listItem->getRawValue();
        }

        return $return;
    }

    public function getType() : ListType
    {
        return $this->type;
    }

    public function jsonSerialize() : array
    {
        return \array_values($this->value);
    }
}
