<?php

declare(strict_types = 1);

namespace PGQL\Argument;

final class ArgumentSet extends \Infinityloop\Utils\ImmutableSet
{
    private array $defaults = [];

    public function __construct(array $arguments)
    {
        foreach ($arguments as $argument) {
            if ($argument instanceof Argument) {
                $this->array[$argument->getName()] = $argument;
                $defaultValue = $argument->getDefaultValue();

                if ($defaultValue instanceof \PGQL\Value\ValidatedValue) {
                    $this->defaults[$argument->getName()] = $defaultValue;
                }

                continue;
            }

            throw new \Exception();
        }
    }

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
            throw new \Exception('Unknown argument.');
        }

        return $this->array[$offset];
    }
}
