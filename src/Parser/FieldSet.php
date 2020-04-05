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

    public function current() : Field
    {
        return parent::current();
    }

    public function offsetGet($offset) : Field
    {
        return parent::offsetGet($offset);
    }

    public function normalize(
        \Graphpinator\DI\TypeResolver $typeResolver,
        \Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions,
        \Graphpinator\Value\ValidatedValueSet $variables
    ) : \Graphpinator\Request\FieldSet
    {
        $normalizedFields = [];

        foreach ($this->getCombinedFields($fragmentDefinitions) as $field) {
            \assert($field instanceof Field);

            $normalizedFields[] = $field->normalize($typeResolver, $fragmentDefinitions, $variables);
        }

        return new \Graphpinator\Request\FieldSet($normalizedFields);
    }

    private function getCombinedFields(\Graphpinator\Parser\Fragment\FragmentSet $fragmentDefinitions) : array
    {
        $allFields = $this->array;

        foreach ($this->fragments as $fragment) {
            foreach ($fragment->getFields($fragmentDefinitions) as $fragmentField) {
                if (\array_key_exists($fragmentField->getName(), $allFields)) {
                    throw new \Exception('Fragment defines field that already exists.');
                }

                $allFields[$fragmentField->getName()] = $fragmentField;
            }
        }

        return $allFields;
    }
}
