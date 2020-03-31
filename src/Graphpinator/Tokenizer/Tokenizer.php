<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Tokenizer;

final class Tokenizer implements \Iterator
{
    use \Nette\SmartObject;

    protected array $sourceCharacters;
    protected int $sourceLength;
    protected bool $skipNotRelevant;
    protected int $tokenStartIndex;
    protected int $currentIndex;
    protected ?Token $token;

    public function __construct(string $source, bool $skipNotRelevant = true)
    {
        $this->sourceCharacters = \preg_split('//u', $source, 0, PREG_SPLIT_NO_EMPTY);
        $this->sourceLength = \count($this->sourceCharacters);
        $this->currentIndex = 0;
        $this->skipNotRelevant = $skipNotRelevant;
    }

    public function getNextToken() : Token
    {
        $this->loadToken();

        if (!$this->valid()) {
            throw new \Exception('Unexpected end.');
        }

        return $this->token;
    }

    public function peekNextToken() : Token
    {
        $index = $this->currentIndex;
        $token = $this->getNextToken();
        $this->currentIndex = $index;

        return $token;
    }

    public function assertNextToken(string $tokenType) : Token
    {
        $token = $this->getNextToken();

        if ($token->getType() !== $tokenType) {
            throw new \Exception('Unexpected token.');
        }

        return $token;
    }

    public function current() : Token
    {
        return $this->token;
    }

    public function key() : int
    {
        return $this->tokenStartIndex;
    }

    public function next() : void
    {
        $this->loadToken();
    }

    public function valid() : bool
    {
        if (!$this->token instanceof Token) {
            return false;
        }

        if ($this->skipNotRelevant && \array_key_exists($this->token->getType(), TokenType::IGNORABLE)) {
            $this->loadToken();

            return $this->valid();
        }

        return true;
    }

    public function rewind() : void
    {
        $this->currentIndex = 0;
        $this->loadToken();
    }

    private function loadToken() : void
    {
        $this->skipWhitespace();
        $this->tokenStartIndex = $this->currentIndex;

        if (!$this->hasChar()) {
            $this->token = null;

            return;
        }

        if (\ctype_alpha($this->currentChar())) {
            $value = $this->getName();
            $lower = \strtolower($value);

            switch ($lower) {
                case 'null':
                    $this->token = new Token(TokenType::NULL);

                    return;
                case 'true':
                    $this->token = new Token(TokenType::TRUE);

                    return;
                case 'false':
                    $this->token = new Token(TokenType::FALSE);

                    return;
                case 'fragment':
                    $this->token = new Token(TokenType::FRAGMENT);

                    return;
                default:
                    $this->token = \array_key_exists($lower, TokenOperation::KEYWORDS)
                        ? new Token(TokenType::OPERATION, $lower)
                        : new Token(TokenType::NAME, $value);

                    return;
            }
        }

        if ($this->currentChar() === '-' || \ctype_digit($this->currentChar())) {
            $numberVal = $this->getInt(true, false);

            if (!$this->hasChar() ||
                !\in_array($this->currentChar(), ['.', 'e', 'E'], true)) {
                $this->token = new Token(TokenType::INT, $numberVal);

                return;
            }

            if ($this->hasChar() && $this->currentChar() === '.') {
                ++$this->currentIndex;
                $numberVal .= '.' . $this->getInt(false, true);
            }

            if ($this->hasChar() && \in_array($this->currentChar(), ['e', 'E'], true)) {
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
                $dots = $this->eatChars(function (string $char) : bool { return $char === '.'; });

                if (\strlen($dots) !== 3) {
                    throw new \Exception();
                }

                $this->token = new Token(TokenType::ELLIP);

                return;
        }

        throw new \Exception('Unknown token');
    }

    private function currentChar() : string
    {
        if ($this->hasChar()) {
            return $this->sourceCharacters[$this->currentIndex];
        }

        throw new \Exception('Enexpected end');
    }

    private function hasChar() : bool
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

        if (!$leadingZeros && $this->hasChar() && $this->currentChar() === '0') {
            throw new \Exception('Leading zeros');
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

        for (; $this->hasChar(); ++$this->currentIndex) {
            if ($condition($this->currentChar()) === true) {
                $value .= $this->currentChar();

                continue;
            }

            break;
        }

        return $value;
    }
}
