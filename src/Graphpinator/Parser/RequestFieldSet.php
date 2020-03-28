<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Parser;

final class RequestFieldSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(array $fields)
    {
        foreach ($fields as $field) {
            if ($field instanceof RequestField) {
                $this->appendUnique($field->getName(), $field);

                continue;
            }

            throw new \Exception();
        }
    }

    public function current() : RequestField
    {
        return parent::current();
    }

    public function offsetGet($offset) : RequestField
    {
        parent::offsetGet($offset);
    }
}
