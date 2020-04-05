<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class ListVal implements Value
{
    use \Nette\SmartObject;

    private array $value;

    public function __construct(array $value)
    {
        $this->value = $value;
    }

    public function normalize(\Graphpinator\Value\ValidatedValueSet $variables) : self
    {
        $return = [];

        foreach ($this->value as $value) {
            \assert($value instanceof Value);

            $return[] = $value->normalize($variables);
        }

        return new self($return);
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
}
