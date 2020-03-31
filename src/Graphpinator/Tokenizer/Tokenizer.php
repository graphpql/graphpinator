<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Tokenizer;

final class Tokenizer implements \Iterator
{
    use \Nette\SmartObject;

    private Source $source;
    protected bool $skipNotRelevant;
    protected ?Token $token;
    protected ?int $tokenStartIndex;

    public function __construct(string $source, bool $skipNotRelevant = true)
    {
        $this->source = new Source($source);
        $this->skipNotRelevant = $skipNotRelevant;
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
        if (!$this->token instanceof Token || !\is_int($this->tokenStartIndex)) {
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
        $this->source->rewind();
        $this->loadToken();
    }

    private function loadToken() : void
    {
        $this->skipWhitespace();

        if (!$this->source->hasChar()) {
            $this->token = null;
            $this->tokenStartIndex = null;

            return;
        }

        $this->tokenStartIndex = $this->source->key();

        if (\ctype_alpha($this->source->getChar())) {
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
                    $this->token = \array_key_exists($lower, OperationType::KEYWORDS)
                        ? new Token(TokenType::OPERATION, $lower)
                        : new Token(TokenType::NAME, $value);

                    return;
            }
        }

        if ($this->source->getChar() === '-' || \ctype_digit($this->source->getChar())) {
            $numberVal = $this->getInt(true, false);

            if (!$this->source->hasChar() ||
                !\in_array($this->source->getChar(), ['.', 'e', 'E'], true)) {
                $this->token = new Token(TokenType::INT, $numberVal);

                return;
            }

            if ($this->source->hasChar() && $this->source->getChar() === '.') {
                $this->source->next();
                $numberVal .= '.' . $this->getInt(false, true);
            }

            if ($this->source->hasChar() && \in_array($this->source->getChar(), ['e', 'E'], true)) {
                $this->source->next();
                $numberVal .= 'e' . $this->getInt(true, true);
            }


            $this->token = new Token(TokenType::FLOAT, $numberVal);

            return;
        }

        switch ($this->source->getChar()) {
            case '"':
                $this->source->next();
                $this->token = new Token(TokenType::STRING, $this->getString());
                $this->source->next();

                return;
            case \PHP_EOL:
                $this->token = new Token(TokenType::NEWLINE);
                $this->source->next();

                return;
            case '$':
                $this->source->next();

                if (\ctype_alpha($this->source->getChar())) {
                    $this->token = new Token(TokenType::VARIABLE, $this->getName());

                    return;
                }

                throw new \Exception('Invalid variable name');
            case TokenType::COMMENT:
                $this->source->next();
                $this->token = new Token(TokenType::COMMENT, $this->getComment());

                return;
            case TokenType::COMMA:
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
                $this->token = new Token($this->source->getChar());
                $this->source->next();

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

        if ($this->source->getChar() === '-') {
            if ($negative) {
                $value .= '-';
            } else {
                throw new \Exception();
            }

            $this->source->next();
        }

        if (!$leadingZeros && $this->source->hasChar() && $this->source->getChar() === '0') {
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

        for (; $this->source->hasChar(); $this->source->next()) {
            if ($condition($this->source->getChar()) === true) {
                $value .= $this->source->getChar();

                continue;
            }

            break;
        }

        return $value;
    }
}
