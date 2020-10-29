<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ListResolvedValue extends \Graphpinator\Value\ListValue implements \Graphpinator\Value\OutputValue
{
    public function __construct(\Graphpinator\Type\ListType $type, array $rawValue)
    {
        $this->type = $type;
        $this->value = $rawValue;
    }

    public function getRawValue() : array
    {
        $return = [];

        foreach ($this->value as $listItem) {
            \assert($listItem instanceof ResolvedValue);

            $return[] = $listItem->getRawValue();
        }

        return $return;
    }

    public function getType() : \Graphpinator\Type\ListType
    {
        return $this->type;
    }

    public function jsonSerialize() : array
    {
        return $this->value;
    }
}
