<?php

declare(strict_types = 1);

namespace Graphpinator\Argument;

final class ArgumentSet extends \Infinityloop\Utils\ImmutableSet
{
    private array $defaults = [];

    public function __construct(array $arguments)
    {
        foreach ($arguments as $argument) {
            if ($argument instanceof Argument) {
                $this->appendUnique($argument->getName(), $argument);
                $defaultValue = $argument->getDefaultValue();

                if ($defaultValue instanceof \Graphpinator\Value\ValidatedValue) {
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
        return parent::offsetGet($offset);
    }
}
