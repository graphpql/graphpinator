<?php

declare(strict_types = 1);

namespace Graphpinator\Source;

final class StringSource implements Source
{
    use \Nette\SmartObject;

    private array $characters;
    private int $numberOfChars;
    private int $currentIndex;

    public function __construct(string $source)
    {
        $this->characters = \preg_split('//u', $source, 0, PREG_SPLIT_NO_EMPTY);
        $this->numberOfChars = \count($this->characters);
        $this->rewind();
    }

    public function hasChar() : bool
    {
        return \array_key_exists($this->currentIndex, $this->characters);
    }

    public function getChar() : string
    {
        if ($this->hasChar()) {
            return $this->characters[$this->currentIndex];
        }

        throw new \Graphpinator\Exception\SourceUnexpectedEnd($this->getPosition());
    }

    public function getPosition() : int
    {
        return $this->currentIndex;
    }

    public function current() : string
    {
        return $this->getChar();
    }

    public function key() : int
    {
        return $this->currentIndex;
    }

    public function next() : void
    {
        ++$this->currentIndex;
    }

    public function valid() : bool
    {
        return $this->hasChar();
    }

    public function rewind() : void
    {
        $this->currentIndex = 0;
    }
}
