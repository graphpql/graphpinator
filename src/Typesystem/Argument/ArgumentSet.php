<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Argument;

/**
 * @method \Graphpinator\Typesystem\Argument\Argument current() : object
 * @method \Graphpinator\Typesystem\Argument\Argument offsetGet($offset) : object
 */
final class ArgumentSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = Argument::class;

    private array $defaults = [];

    public function getRawDefaults() : array
    {
        return $this->defaults;
    }

    public function offsetSet($offset, $value) : void
    {
        \assert($value instanceof Argument);

        parent::offsetSet($offset, $value);

        $defaultValue = $value->getDefaultValue();

        if ($defaultValue instanceof \Graphpinator\Value\ArgumentValue) {
            $this->defaults[$value->getName()] = $defaultValue->getValue()->getRawValue();
        }
    }

    protected function getKey(object $object) : string
    {
        \assert($object instanceof Argument);

        return $object->getName();
    }
}
