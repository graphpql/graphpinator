<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver\Value;

final class ListValue extends \Graphpinator\Resolver\Value\ValidatedValue implements \Iterator, \Countable
{
    public function __construct($list, \Graphpinator\Type\ListType $type)
    {
        if (!\is_iterable($list)) {
            throw new \Exception('Value must be list or null.');
        }

        $value = [];

        foreach ($list as $item) {
            $value[] = $type->getInnerType()->createValue($item);
        }

        parent::__construct($value, $type);
    }

    public function getRawValue() : array
    {
        $return = [];

        foreach ($this->value as $listItem) {
            \assert($listItem instanceof ValidatedValue);

            $return[] = $listItem->getRawValue();
        }

        return $return;
    }

    public function printValue(bool $prettyPrint) : string
    {
        $component = [];

        foreach ($this->value as $value) {
            \assert($value instanceof ValidatedValue);

            $component[] = $value->printValue(true);
        }

        return $prettyPrint
            ? '[' . \implode(', ', $component) . ']'
            : '[' . \implode(',', $component) . ']';
    }

    public function current() : ValidatedValue
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
