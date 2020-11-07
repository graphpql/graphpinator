<?php

declare(strict_types = 1);

namespace Graphpinator\Argument;

/**
 * @method Argument current() : object
 * @method Argument offsetGet($offset) : object
 */
final class ArgumentSet extends \Infinityloop\Utils\ImplicitObjectMap implements \Graphpinator\Printable\PrintableSet
{
    protected const INNER_CLASS = Argument::class;

    private array $defaults = [];

    public function getRawDefaults() : array
    {
        return $this->defaults;
    }

    public function offsetSet($offset, $object): void
    {
        \assert($object instanceof Argument);

        parent::offsetSet($offset, $object);

        $defaultValue = $object->getDefaultValue();

        if ($defaultValue instanceof \Graphpinator\Value\InputedValue) {
            $this->defaults[$object->getName()] = $defaultValue->getRawValue();
        }
    }

    protected function getKey(object $object) : string
    {
        \assert($object instanceof Argument);

        return $object->getName();
    }
}
