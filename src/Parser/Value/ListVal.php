<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class ListVal implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    public function __construct(
        private array $value,
    ) {}

    public function getValue() : array
    {
        return $this->value;
    }

    public function getRawValue() : array
    {
        $return = [];

        foreach ($this->value as $value) {
            \assert($value instanceof Value);

            $return[] = $value->getRawValue();
        }

        return $return;
    }

    public function accept(ValueVisitor $valueVisitor) : mixed
    {
        return $valueVisitor->visitListVal($this);
    }
}
