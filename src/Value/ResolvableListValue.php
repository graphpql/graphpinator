<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ResolvableListValue implements ResolvableValue, \Iterator, \Countable
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\ListType $type;
    private array $value;

    public function __construct(\Graphpinator\Type\ListType $type, array $rawValue)
    {
        $this->type = $type;
        $this->value = $rawValue;
    }

    public function getRawValue() : array
    {
        $return = [];

        foreach ($this->value as $listItem) {
            \assert($listItem instanceof ResolvableValue);

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

    public function isNull() : bool
    {
        return false;
    }

    public function current() : ResolvableValue
    {
        return \current($this->value);
    }

    public function next() : void
    {
        \next($this->value);
    }

    public function key() : int
    {
        return \key($this->value);
    }

    public function valid() : bool
    {
        return \key($this->value) !== null;
    }

    public function rewind() : void
    {
        \reset($this->value);
    }

    public function count() : int
    {
        return \count($this->value);
    }
}
