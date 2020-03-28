<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Utils;

trait TFieldContainer
{
    protected \Infinityloop\Graphpinator\Field\FieldSet $fields;

    public function getFields() : \Infinityloop\Graphpinator\Field\FieldSet
    {
        return $this->fields;
    }

    public function resolveFields(?\Infinityloop\Graphpinator\Parser\RequestFieldSet $requestedFields, \Infinityloop\Graphpinator\Field\ResolveResult $parent) : array
    {
        if ($requestedFields === null) {
            throw new \Exception('Composite type without fields specified.');
        }

        $resolved = [];

        foreach ($requestedFields as $request) {
            if ($request->getConditionType() instanceof \Infinityloop\Graphpinator\Type\Contract\NamedDefinition &&
                !$parent->getType()->isInstanceOf($request->getConditionType())) {
                continue;
            }

            $field = $this->fields[$request->getName()];
            $arguments = new \Infinityloop\Graphpinator\Value\ValidatedValueSet($request->getArguments(), $field->getArguments());

            $resolved[$field->getName()] = $field->getType()->resolveFields(
                $request->getChildren(),
                $field->resolve($parent, $arguments),
            );
        }

        return $resolved;
    }
}
