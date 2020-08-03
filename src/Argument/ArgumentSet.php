<?php

declare(strict_types = 1);

namespace Graphpinator\Argument;

final class ArgumentSet extends \Infinityloop\Utils\ObjectSet implements \Graphpinator\Printable\PrintableSet
{
    protected const INNER_CLASS = Argument::class;

    private array $defaults = [];

    public function getDefaults() : array
    {
        return $this->defaults;
    }

    public function current() : Argument
    {
        return parent::current();
    }

    public function offsetGet($offset) : Argument
    {
        if (!$this->offsetExists($offset)) {
            throw new \Graphpinator\Exception\Argument\ArgumentNotDefined();
        }

        return $this->array[$offset];
    }

    protected function getKey($object) : string
    {
        $defaultValue = $object->getDefaultValue();

        if ($defaultValue instanceof \Graphpinator\Resolver\Value\ValidatedValue) {
            $this->defaults[$object->getName()] = $defaultValue;
        }

        return $object->getName();
    }
}
