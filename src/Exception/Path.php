<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class Path implements \JsonSerializable
{
    use \Nette\SmartObject;

    private array $path;

    public function __construct(array $path)
    {
        $this->path = $path;
    }

    public function jsonSerialize() : array
    {
        return $this->path;
    }
}
