<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class Path implements \JsonSerializable
{
    use \Nette\SmartObject;

    private array $path;

    public function __construct(array $path = [])
    {
        $this->path = $path;
    }

    public function add(string $pathItem) : self
    {
        $this->path[] = $pathItem;

        return $this;
    }

    public function pop() : self
    {
        \array_pop($this->path);

        return $this;
    }

    public function jsonSerialize() : array
    {
        return $this->path;
    }
}
