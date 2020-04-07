<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class FieldSet extends \Graphpinator\ClassSet
{
    public const INNER_CLASS = Field::class;

    private \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet $fragments;

    public function __construct(array $fields, \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet $fragments)
    {
        parent::__construct($fields);
        $this->fragments = $fragments;
    }

    public function getFragmentSpreads() : \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet
    {
        return $this->fragments;
    }

    public function normalize(
        \Graphpinator\Type\Resolver $resolver,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions
    ) : \Graphpinator\Normalizer\FieldSet
    {
        $normalizedFields = [];

        foreach ($this->getCombinedFields($fragmentDefinitions) as $field) {
            \assert($field instanceof Field);

            $normalizedFields[] = $field->normalize($resolver, $fragmentDefinitions);
        }

        return new \Graphpinator\Normalizer\FieldSet($normalizedFields);
    }

    public function current() : Field
    {
        return parent::current();
    }

    public function offsetGet($offset) : Field
    {
        return parent::offsetGet($offset);
    }

    private function getCombinedFields(\Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions) : array
    {
        $allFields = $this->array;

        foreach ($this->fragments as $fragment) {
            foreach ($fragment->getFields($fragmentDefinitions) as $fragmentField) {
                if (\array_key_exists($fragmentField->getName(), $allFields)) {
                    throw new \Exception('Fragment defines field that already exists in selection.');
                }

                $allFields[$fragmentField->getName()] = $fragmentField;
            }
        }

        return $allFields;
    }
}
