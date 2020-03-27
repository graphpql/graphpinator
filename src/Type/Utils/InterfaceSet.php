<?php

declare(strict_types = 1);

namespace PGQL\Type\Utils;

final class InterfaceSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(array $interfaces)
    {
        foreach ($interfaces as $interface) {
            if ($interface instanceof \PGQL\Type\InterfaceType) {
                $this->array[$interface->getName()] = $interface;

                continue;
            }

            throw new \Exception();
        }
    }

    public function current() : \PGQL\Type\InterfaceType
    {
        return parent::current();
    }

    public function offsetGet($offset) : \PGQL\Type\InterfaceType
    {
        if (!$this->offsetExists($offset)) {
            throw new \Exception('Unknown interface.');
        }

        return $this->array[$offset];
    }
}
