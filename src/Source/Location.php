<?php

declare(strict_types = 1);

namespace Graphpinator\Source;

final class Location implements \JsonSerializable
{
    use \Nette\SmartObject;

    private int $line;
    private int $column;

    public function __construct(int $line, int $column)
    {
        $this->line = $line;
        $this->column = $column;
    }

    public function jsonSerialize() : array
    {
        return [
            'line' => $this->line,
            'column' => $this->column,
        ];
    }

    public function getLine() : int
    {
        return $this->line;
    }

    public function getColumn() : int
    {
        return $this->column;
    }
}
