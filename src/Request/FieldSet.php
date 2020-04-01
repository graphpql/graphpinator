<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class FieldSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(array $fields)
    {
        foreach ($fields as $field) {
            if ($field instanceof Field) {
                $this->appendUnique($field->getName(), $field);

                continue;
            }

            throw new \Exception();
        }
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
