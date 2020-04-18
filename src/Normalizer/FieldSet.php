<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class FieldSet extends \Graphpinator\Utils\ClassSet
{
    public const INNER_CLASS = Field::class;

    private \Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet $fragments;

    public function __construct(array $fields, ?\Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet $fragments = null)
    {
        parent::__construct($fields);
        $this->fragments = $fragments ?? new \Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet([]);
    }

    public function getFragments() : \Graphpinator\Normalizer\FragmentSpread\FragmentSpreadSet
    {
        return $this->fragments;
    }

    public function applyVariables(\Graphpinator\Resolver\VariableValueSet $variables) : self
    {
        $fields = [];

        foreach ($this as $field) {
            $fields[] = $field->applyVariables($variables);
        }

        return new self(
            $fields,
            $this->fragments->applyVariables($variables),
        );
    }

    public function current() : Field
    {
        return parent::current();
    }

    public function offsetGet($offset) : Field
    {
        return parent::offsetGet($offset);
    }
}
