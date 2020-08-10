<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class FieldSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Field::class;

    private \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet $fragments;

    public function __construct(array $fields, ?\Graphpinator\Parser\FragmentSpread\FragmentSpreadSet $fragments = null)
    {
        parent::__construct($fields);

        $this->fragments = $fragments
            ?? new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([]);
    }

    public function current() : Field
    {
        return parent::current();
    }

    public function offsetGet($offset) : Field
    {
        return parent::offsetGet($offset);
    }

    public function getFragmentSpreads() : \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet
    {
        return $this->fragments;
    }

    public function validateCycles(\Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions, array $stack) : void
    {
        foreach ($this as $field) {
            if ($field->getFields() instanceof self) {
                $field->getFields()->validateCycles($fragmentDefinitions, $stack);
            }
        }

        foreach ($this->getFragmentSpreads() as $spread) {
            if (!$spread instanceof \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread) {
                continue;
            }

            $fragmentDefinitions[$spread->getName()]->validateCycles($fragmentDefinitions, $stack);
        }
    }

    public function normalize(
        \Graphpinator\Type\Container\Container $typeContainer,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions
    ) : \Graphpinator\Normalizer\FieldSet
    {
        $normalized = [];

        foreach ($this as $field) {
            $normalized[] = $field->normalize($typeContainer, $fragmentDefinitions);
        }

        return new \Graphpinator\Normalizer\FieldSet(
            $normalized,
            $this->fragments->normalize($typeContainer, $fragmentDefinitions),
        );
    }

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
