<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\FragmentSpread;

final class FragmentSpreadSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(array $data)
    {
        parent::__construct($data);
    }

    public function current() : FragmentSpread
    {
        return parent::current();
    }

    public function offsetGet($offset) : FragmentSpread
    {
        return parent::offsetGet($offset);
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
