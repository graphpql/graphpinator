<?php

declare(strict_types = 1);

namespace Graphpinator\Tokenizer;

final class TokenContainer implements \IteratorAggregate
{
    use \Nette\SmartObject;

    private array $tokens = [];
    private int $currentIndex = 0;

    public function __construct(\Graphpinator\Source\Source $source, bool $skipNotRelevant = true)
    {
        $tokenizer = new \Graphpinator\Tokenizer\Tokenizer($source, $skipNotRelevant);

        foreach ($tokenizer as $token) {
            $this->tokens[] = $token;
        }
    }

    public function hasPrev() : bool
    {
        return \array_key_exists($this->currentIndex - 1, $this->tokens);
    }

    public function hasNext() : bool
    {
        return \array_key_exists($this->currentIndex + 1, $this->tokens);
    }

    public function isEmpty() : bool
    {
        return \count($this->tokens) === 0;
    }

    public function getCurrent() : Token
    {
        return $this->tokens[$this->currentIndex];
    }

    public function getPrev() : Token
    {
        if (!$this->hasPrev()) {
            throw new \Graphpinator\Exception\Parser\UnexpectedEnd();
        }

        --$this->currentIndex;

        return $this->tokens[$this->currentIndex];
    }

    public function getNext() : Token
    {
        if (!$this->hasNext()) {
            throw new \Graphpinator\Exception\Parser\UnexpectedEnd($this->getCurrent()->getLocation());
        }

        ++$this->currentIndex;

        return $this->tokens[$this->currentIndex];
    }

    public function peekNext() : Token
    {
        if (!$this->hasNext()) {
            throw new \Graphpinator\Exception\Parser\UnexpectedEnd($this->getCurrent()->getLocation());
        }

        return $this->tokens[$this->currentIndex + 1];
    }

    public function assertNext(string $tokenType, string $exceptionClass) : Token
    {
        $token = $this->getNext();

        if ($token->getType() !== $tokenType) {
            throw new $exceptionClass();
        }

        return $token;
    }

    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->tokens);
    }
}
