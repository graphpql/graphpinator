<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

class FieldSet extends \Graphpinator\ClassSet
{
    public const INNER_CLASS = Field::class;

    public function current() : Field
    {
        return parent::current();
    }

    public function offsetGet($offset) : Field
    {
        return parent::offsetGet($offset);
    }

    public function applyVariables(VariableValueSet $variables) : self
    {
        $fields = [];

        foreach ($this as $field) {
            $fields[] = $field->applyVariables($variables);
        }

        return new self($fields);
    }
}
