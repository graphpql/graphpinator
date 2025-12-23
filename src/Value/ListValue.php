<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\ListType;
use Graphpinator\Value\Contract\InputedValue;
use Graphpinator\Value\Contract\InputedValueVisitor;
use Graphpinator\Value\Contract\OutputValue;
use Graphpinator\Value\Contract\Value;

/**
 * @implements \IteratorAggregate<Value>
 */
final class ListValue implements InputedValue, OutputValue, \IteratorAggregate
{
    public function __construct(
        public readonly ListType $type,
        /** @var list<Value> */
        public array $value,
    )
    {
    }

    #[\Override]
    public function accept(InputedValueVisitor $visitor) : mixed
    {
        return $visitor->visitList($this);
    }

    #[\Override]
    public function getType() : ListType
    {
        return $this->type;
    }

    #[\Override]
    public function getRawValue() : array
    {
        $return = [];

        foreach ($this->value as $listItem) {
            $return[] = $listItem->getRawValue();
        }

        return $return;
    }

    /**
     * @return \ArrayIterator<Value>
     */
    #[\Override]
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->value);
    }

    #[\Override]
    public function jsonSerialize() : array
    {
        return $this->value;
    }
}
