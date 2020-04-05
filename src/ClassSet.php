<?php

declare(strict_types = 1);

namespace Graphpinator;

abstract class ClassSet extends \Infinityloop\Utils\ImmutableSet
{
    public const INNER_CLASS = '';

    public function __construct(array $data)
    {
        $validatedData = [];

        foreach ($data as $object) {
            if (\get_class($object) !== static::INNER_CLASS) {
                throw new \Exception('Invalid input.');
            }

            $name = $object->getName();

            if ($this->offsetExists($name)) {
                throw new \Exception('Duplicated item.');
            }

            $validatedData[$name] = $object;
        }

        parent::__construct($validatedData);
    }
}
