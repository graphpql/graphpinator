<?php

declare(strict_types = 1);

namespace PGQL\Type\Utils;

trait TFieldContainer
{
    protected \PGQL\Field\FieldSet $fields;

    public function getFields() : \PGQL\Field\FieldSet
    {
        return $this->fields;
    }

    public function resolveFields(?\PGQL\Parser\RequestFieldSet $requestedFields, \PGQL\Field\ResolveResult $parent) : array
    {
        if ($requestedFields === null) {
            throw new \Exception('Composite type without fields specified.');
        }

        $resolved = [];

        foreach ($requestedFields as $request) {
            if ($request->getConditionType() instanceof \PGQL\Type\Contract\NamedDefinition &&
                !$parent->getType()->isInstanceOf($request->getConditionType())) {
                continue;
            }

            $field = $this->fields[$request->getName()];
            $arguments = new \PGQL\Value\ValidatedValueSet($request->getArguments(), $field->getArguments());

            $resolved[$field->getName()] = $field->getType()->resolveFields(
                $request->getChildren(),
                $field->resolve($parent, $arguments),
            );
        }

        return $resolved;
    }
}
