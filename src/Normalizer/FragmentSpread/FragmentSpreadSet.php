<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\FragmentSpread;

final class FragmentSpreadSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = FragmentSpread::class;

    public function current() : FragmentSpread
    {
        return parent::current();
    }

    public function offsetGet($offset) : FragmentSpread
    {
        if (!$this->offsetExists($offset)) {
            throw new \Graphpinator\Exception\Normalizer\UnknownFragment();
        }

        return $this->array[$offset];
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : self
    {
        $spreads = [];

        foreach ($this as $spread) {
            $spreads[] = $spread->applyVariables($variables);
        }

        return new self($spreads);
    }
}
