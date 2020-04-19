<?php

declare(strict_types = 1);

namespace Graphpinator\Source;

final class StringSource implements Source
{
    use \Nette\SmartObject;

    private array $characters;
    private int $numberOfChars;
    private int $currentIndex;
    private int $currentLine;
    private int $currentColumn;

    public function __construct(string $source)
    {
        $this->characters = \preg_split('//u', $source, -1, PREG_SPLIT_NO_EMPTY);
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

        throw new \Graphpinator\Exception\Tokenizer\SourceUnexpectedEnd($this->getLocation());
    }

    public function getLocation() : Location
    {
        return new Location($this->currentLine, $this->currentColumn);
    }

    public function getNumberOfChars() : int
    {
        return $this->numberOfChars;
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
        if ($this->getChar() === \PHP_EOL) {
            ++$this->currentLine;
            $this->currentColumn = 0;
        } else {
            ++$this->currentColumn;
        }

        ++$this->currentIndex;
    }

    public function valid() : bool
    {
        return $this->hasChar();
    }

    public function rewind() : void
    {
        $this->currentIndex = 0;
        $this->currentLine = 0;
        $this->currentColumn = 0;
    }
}
