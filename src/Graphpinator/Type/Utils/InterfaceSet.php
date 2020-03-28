<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Utils;

final class InterfaceSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(array $interfaces)
    {
        foreach ($interfaces as $interface) {
            if ($interface instanceof \Infinityloop\Graphpinator\Type\InterfaceType) {
                $this->appendUnique($interface->getName(), $interface);

                continue;
            }

            throw new \Exception();
        }
    }

    public function current() : \Infinityloop\Graphpinator\Type\InterfaceType
    {
        return parent::current();
    }

    public function offsetGet($offset) : \Infinityloop\Graphpinator\Type\InterfaceType
    {
        if (!$this->offsetExists($offset)) {
            throw new \Exception('Unknown interface.');
        }

        return $this->array[$offset];
    }
}
