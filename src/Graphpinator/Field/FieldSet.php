<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Field;

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
        if (!$this->offsetExists($offset)) {
            throw new \Exception('Unknown field.');
        }

        return $this->array[$offset];
    }
}
