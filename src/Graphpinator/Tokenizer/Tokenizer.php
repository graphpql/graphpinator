<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Tokenizer;

class Tokenizer implements \Iterator
{
    use \Nette\SmartObject;

    protected array $sourceCharacters;
    protected bool $skipNotRelevant;
    protected int $tokenStartIndex;
    protected int $currentIndex;
    protected int $sourceLength;
    protected bool $valid = true;
    protected ?Token $token;

    public function __construct(string $source, bool $skipNotRelevant = true)
    {
        $this->sourceCharacters = \str_split($source);
        $this->skipNotRelevant = $skipNotRelevant;
    }

    public function current() : Token
    {
        return $this->token;
    }

    public function next() : void
    {
        $this->skipWhitespace();
        $this->tokenStartIndex = $this->currentIndex;

        if (!$this->hasNextChar()) {
            $this->valid = false;

            return;
        }

        if (\ctype_alpha($this->currentChar())) {
            $this->token = new Token(TokenType::NAME, $this->getName());

            return;
        }

        if ($this->currentChar() === '-' || \ctype_digit($this->currentChar())) {
            $numberVal = $this->getInt(true, false);

            if (!$this->hasNextChar() ||
                !\in_array($this->currentChar(), ['.', 'e', 'E'], true)) {
                $this->token = new Token(TokenType::INT, $numberVal);

                return;
            }

            if ($this->hasNextChar() && $this->currentChar() === '.') {
                ++$this->currentIndex;
                $numberVal .= '.' . $this->getInt(false, true);
            }

            if ($this->hasNextChar() && \in_array($this->currentChar(), ['e', 'E'], true)) {
                ++$this->currentIndex;
                $numberVal .= 'e' . $this->getInt(true, true);
            }


            $this->token = new Token(TokenType::FLOAT, $numberVal);

            return;
        }

        switch ($this->currentChar()) {
            case '"':
                ++$this->currentIndex;
                $this->token = new Token(TokenType::STRING, $this->getString());
                ++$this->currentIndex;

                return;
            case \PHP_EOL:
                $this->token = new Token(TokenType::NEWLINE);
                ++$this->currentIndex;

                return;
            case TokenType::COMMENT:
                ++$this->currentIndex;
                $this->token = new Token(TokenType::COMMENT, $this->getComment());

                return;
            case TokenType::COMMA:
            case TokenType::VAR:
            case TokenType::AMP:
            case TokenType::PIPE:
            case TokenType::EXCL:
            case TokenType::PAR_O:
            case TokenType::PAR_C:
            case TokenType::CUR_O:
            case TokenType::CUR_C:
            case TokenType::SQU_O:
            case TokenType::SQU_C:
            case TokenType::COLON:
            case TokenType::EQUAL:
            case TokenType::AT:
                $this->token = new Token($this->currentChar());
                ++$this->currentIndex;

                return;
            case '.':
                $dotCount = 0;

                for (; $this->hasNextChar(); ++$this->currentIndex) {
                    if ($this->currentChar() !== '.') {
                        break;
                    }

                    ++$dotCount;
                }

                if ($dotCount !== 3) {
                    throw new \Exception();
                }

                $this->token = new Token(TokenType::ELLIP);

                return;
        }

        throw new \Exception('Invalid token');
    }

    public function key() : int
    {
        return $this->tokenStartIndex;
    }

    public function valid() : bool
    {
        if (!$this->valid || !$this->token instanceof Token) {
            return false;
        }

        if ($this->skipNotRelevant && \array_key_exists($this->token->getType(), TokenType::IGNORABLE)) {
            $this->next();
            return $this->valid();
        }

        return true;
    }

    public function rewind() : void
    {
        $this->currentIndex = 0;
        $this->sourceLength = \count($this->sourceCharacters);
        $this->next();
    }

    private function currentChar() : string
    {
        if ($this->hasNextChar()) {
            return $this->sourceCharacters[$this->currentIndex];
        }

        throw new \Exception('Enexpected end');
    }

    private function hasNextChar() : bool
    {
        return $this->currentIndex < $this->sourceLength;
    }

    private function skipWhiteSpace() : void
    {
        $this->eatChars(function (string $char) : bool { return $char !== \PHP_EOL && \ctype_space($char); });
    }

    private function getComment() : string
    {
        return $this->eatChars(function (string $char) : bool {return $char !== \PHP_EOL; });
    }

    private function getString() : string
    {
        return $this->eatChars(function (string $char) : bool { return $char !== '"'; });
    }

    private function getInt(bool $negative = true, bool $leadingZeros = true) : string
    {
        $value = '';

        if ($this->currentChar() === '-') {
            if ($negative) {
                $value .= '-';
            } else {
                throw new \Exception();
            }

            ++$this->currentIndex;
        }

        for (; $this->hasNextChar(); ++$this->currentIndex) {
            if ($this->currentChar() === '0') {
                if ($leadingZeros) {
                    $value .= '0';

                    continue;
                }

                throw new \Exception();
            }

            break;
        }

        $value .=  $this->eatChars(function (string $char) : bool { return \ctype_digit($char); });

        if (!\is_numeric($value)) {
            throw new \Exception('Invalid numeric value');
        }

        return $value;
    }

    private function getName() : string
    {
        return $this->eatChars(function (string $char) : bool { return \ctype_alnum($char); });
    }

    private function eatChars(callable $condition) : string
    {
        $value = '';

        for (; $this->hasNextChar(); ++$this->currentIndex) {
            if ($condition($this->currentChar()) === true) {
                $value .= $this->currentChar();

                continue;
            }

            break;
        }

        return $value;
    }
}
