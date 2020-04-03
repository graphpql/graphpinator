<?php

declare(strict_types = 1);

namespace Graphpinator\Parser;

final class FieldSet
{
    use \Nette\SmartObject;

    private array $children;
    private array $fragments;

    public function __construct(array $fields, array $fragments)
    {
        $this->children = $fields;
        $this->fragments = $fragments;
    }

    public function addField(Field $field)
    {
        if (\array_key_exists($field->getName(), $this->children)) {
            throw new \Exception('Duplicated field');
        }

        $this->children[$field->getName()] = $field;
    }

    public function normalize(
        \Graphpinator\DI\TypeResolver $typeResolver,
        \Graphpinator\Type\Contract\Definition $parent,
        array $fragmentDefinitions,
        \Graphpinator\Value\ValidatedValueSet $variables
    ) : \Graphpinator\Request\FieldSet
    {
        if (!$parent instanceof \Graphpinator\Type\Utils\FieldContainer) {
            throw new \Exception();
        }

        $availableFields = $parent->getFields();
        $this->expandFragments($fragmentDefinitions);
        $fields = [];

        foreach ($this->children as $field) {
            \assert($field instanceof Field);

            if (!$availableFields->offsetExists($field->getName())) {
                throw new \Exception('Unknown field.');
            }

            $fields[] = $field->normalize($typeResolver, $availableFields[$field->getName()], $fragmentDefinitions, $variables);
        }

        return new \Graphpinator\Request\FieldSet($fields);
    }

    private function expandFragments(array $fragmentDefinitions) : void
    {
        foreach ($this->fragments as $fragment) {
            \assert($fragment instanceof \Graphpinator\Parser\FragmentSpread\FragmentSpread);

            $fragment->spread($this, $fragmentDefinitions);
        }
    }
}
