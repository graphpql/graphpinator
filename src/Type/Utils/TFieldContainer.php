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

    public function resolveFields(?array $requestedFields, \PGQL\Field\ResolveResult $parentValue) : array
    {
        if ($requestedFields === null) {
            throw new \Exception('Composite type without fields specified.');
        }

        $resolved = [];

        foreach ($requestedFields as $request) {
            \assert($request instanceof \PGQL\RequestField);

            if (\is_string($request->getTypeCondition()) &&
                $request->getTypeCondition() !== $parentValue->getType()->getNamedType()->getName()) {
                continue;
            }

            $field = $this->fields[$request->getName()];
            $arguments = new \PGQL\Value\ValidatedValueSet($request->getArguments(), $field->getArguments());

            $resolved[$field->getName()] = $field->getType()->resolveFields(
                $request->getChildren(),
                $field->resolve($parentValue, $arguments),
            );
        }

        return $resolved;
    }
}
