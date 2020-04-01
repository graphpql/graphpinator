<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Utils;

trait TFieldContainer
{
    protected \Graphpinator\Field\FieldSet $fields;

    public function getFields() : \Graphpinator\Field\FieldSet
    {
        return $this->fields;
    }

    public function resolveFields(?\Graphpinator\Request\FieldSet $requestedFields, \Graphpinator\Field\ResolveResult $parent) : array
    {
        if ($requestedFields === null) {
            throw new \Exception('Composite type without fields specified.');
        }

        $resolved = [];

        foreach ($requestedFields as $request) {
            if ($request->getConditionType() instanceof \Graphpinator\Type\Contract\NamedDefinition &&
                !$parent->getType()->isInstanceOf($request->getConditionType())) {
                continue;
            }

            $field = $this->fields[$request->getName()];
            $arguments = new \Graphpinator\Value\ValidatedValueSet($request->getArguments(), $field->getArguments());

            $resolved[$request->getAlias()] = $field->getType()->resolveFields(
                $request->getChildren(),
                $field->resolve($parent, $arguments),
            );
        }

        return $resolved;
    }
}
