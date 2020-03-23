<?php

declare(strict_types = 1);

namespace PGQL\Argument;

final class ArgumentSet implements \Iterator, \ArrayAccess
{
    use \Nette\SmartObject;

    private array $arguments = [];
    private array $defaults = [];

    public function __construct(array $arguments)
    {
        foreach ($arguments as $argument) {
            if ($argument instanceof Argument) {
                $this->arguments[$argument->getName()] = $argument;
                $defaultValue = $argument->getDefaultValue();

                if ($defaultValue instanceof \PGQL\Value\InputValue) {
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
        return \current($this->arguments);
    }

    public function next() : void
    {
        \next($this->arguments);
    }

    public function key() : int
    {
        return \key($this->arguments);
    }

    public function valid() : bool
    {
        return \key($this->arguments) !== null;
    }

    public function rewind() : void
    {
        \reset($this->arguments);
    }

    public function offsetExists($offset) : bool
    {
        return \array_key_exists($offset, $this->arguments);
    }

    public function offsetGet($offset) : Argument
    {
        if (!$this->offsetExists($offset)) {
            throw new \Exception('Unknown argument.');
        }
        
        return $this->arguments[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new \Exception();
    }

    public function offsetUnset($offset)
    {
        throw new \Exception();
    }
}
