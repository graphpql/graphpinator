<?php

declare(strict_types = 1);

namespace Graphpinator;

final class Json implements \Countable, \IteratorAggregate, \ArrayAccess, \Serializable
{
    use \Nette\SmartObject;

    private ?string $string;
    private ?\stdClass $data;
    private bool $valid;

    private function __construct(?string $json, ?\stdClass $data)
    {
        $this->string = $json;
        $this->data = $data;
        $this->valid = false;
    }

    public static function fromString(string $json) : self
    {
        return new static($json, null);
    }

    public static function fromObject(\stdClass $data) : self
    {
        return new static(null, $data);
    }

    public function toString() : string
    {
        $this->loadString();

        return $this->string;
    }

    public function toArray() : \stdClass
    {
        $this->loadObject();

        return $this->data;
    }

    public function isValid() : bool
    {
        $this->loadString();
        $this->loadObject();

        return $this->valid;
    }

    public function count() : int
    {
        $this->loadObject();

        return \count($this->data);
    }

    public function getIterator() : \Iterator
    {
        $this->loadObject();

        return new \ArrayIterator($this->data);
    }

    public function offsetExists($offset) : bool
    {
        $this->loadObject();

        return \property_exists($this->data, $offset);
    }

    /** @return int|string|bool|array|\stdClass */
    public function offsetGet($offset)
    {
        $this->loadObject();

        return $this->data->{$offset};
    }

    public function offsetSet($offset, $value) : void
    {
        $this->loadObject();
        $this->data->{$offset} = $value;
        $this->string = null;
    }

    public function offsetUnset($offset) : void
    {
        $this->loadObject();
        unset($this->data->{$offset});
        $this->string = null;
    }

    public function serialize() : string
    {
        return $this->toString();
    }

    public function unserialize($serialized) : self
    {
        return self::fromString($serialized);
    }

    private function loadString() : void
    {
        if (\is_string($this->string)) {
            return;
        }

        try {
            $this->string = \json_encode($this->data, \JSON_THROW_ON_ERROR);
            $this->valid = true;
        } catch (\JsonException $exception) {
            $this->valid = false;
        }
    }

    private function loadObject() : void
    {
        if ($this->data instanceof \stdClass) {
            return;
        }

        try {
            $this->data = \json_decode($this->string, false, 512, \JSON_THROW_ON_ERROR);
            $this->valid = true;
        } catch (\JsonException $exception) {
            $this->valid = false;
        }
    }

    public function __toString() : string
    {
        return $this->toString();
    }

    public function __isset($offset) : bool
    {
        return $this->offsetExists($offset);
    }

    /** @return int|string|bool|array|\stdClass */
    public function __get($offset)
    {
        return $this->offsetGet($offset);
    }

    public function __set($offset, $value) : void
    {
        $this->offsetSet($offset, $value);
    }

    public function __unset($offset) : void
    {
        $this->offsetUnset($offset);
    }
}
