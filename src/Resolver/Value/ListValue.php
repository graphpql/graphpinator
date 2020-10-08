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

    public function printValue(bool $prettyPrint = false, int $indentLevel = 0) : string
    {
        $component = [];
        $indentation = \str_repeat('  ', $indentLevel);

        foreach ($this->value as $value) {
            \assert($value instanceof ValidatedValue);

            $prettyPrint
                ? $component[] = $indentation . $value->printValue($prettyPrint, $indentLevel) . ',' . \PHP_EOL
                : $component[] = $value->printValue($prettyPrint, $indentLevel);
        }

        if (!$component) {
            return '[]';
        }

        if ($prettyPrint) {
            foreach ($component as $key => $value) {
                $component[$key] = \str_replace(\PHP_EOL, \PHP_EOL . $indentation, $value);
            }
        }

        return $prettyPrint
            ? '[' . \PHP_EOL . $indentation . \implode($component) . ']'
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
