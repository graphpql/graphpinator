<?php

declare(strict_types = 1);

namespace Graphpinator\Tokenizer;

final class Tokenizer implements \Iterator
{
    use \Nette\SmartObject;

    private \Graphpinator\Source\Source $source;
    protected bool $skipNotRelevant;
    protected ?Token $token = null;
    protected ?int $tokenStartIndex = null;

    private const ESCAPE_MAP = [
        '"' => '"',
        '\\' => '\\',
        '/' => '/',
        'b' => "\u{0008}",
        'f' => "\u{000C}",
        'n' => "\u{000A}",
        'r' => "\u{000D}",
        't' => "\u{0009}",
    ];

    public function __construct(\Graphpinator\Source\Source $source, bool $skipNotRelevant = true)
    {
        $this->source = $source;
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

        if ($this->source->getChar() === '_' || \ctype_alpha($this->source->getChar())) {
            $this->createWordToken();

            return;
        }

        if ($this->source->getChar() === '-' || \ctype_digit($this->source->getChar())) {
            $this->createNumericToken();

            return;
        }

        switch ($this->source->getChar()) {
            case '"':
                $quotes = $this->eatChars(static function (string $char) : bool { return $char === '"'; });

                switch (\strlen($quotes)) {
                    case 1:
                        $this->token = new Token(TokenType::STRING, $this->eatString());

                        return;
                    case 2:
                        $this->token = new Token(TokenType::STRING, '');

                        return;
                    case 3:
                        $this->token = new Token(TokenType::STRING, $this->eatBlockString());

                        return;
                    default:
                        throw new \Exception('Invalid string literal');
                }
            case \PHP_EOL:
                $this->token = new Token(TokenType::NEWLINE);
                $this->source->next();

                return;
            case TokenType::VARIABLE:
                $this->source->next();

                if (\ctype_alpha($this->source->getChar())) {
                    $this->token = new Token(TokenType::VARIABLE, $this->eatName());

                    return;
                }

                throw new \Exception('Missing variable name');
            case TokenType::DIRECTIVE:
                $this->source->next();

                if (\ctype_alpha($this->source->getChar())) {
                    $this->token = new Token(TokenType::DIRECTIVE, $this->eatName());

                    return;
                }

                throw new \Exception('Missing directive name');
            case TokenType::COMMENT:
                $this->source->next();
                $this->token = new Token(TokenType::COMMENT, $this->eatComment());

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
                $this->token = new Token($this->source->getChar());
                $this->source->next();

                return;
            case '.':
                $dots = $this->eatChars(static function (string $char) : bool { return $char === '.'; });

                if (\strlen($dots) !== 3) {
                    throw new \Exception('Invalid ellipsis');
                }

                $this->token = new Token(TokenType::ELLIP);

                return;
        }

        throw new \Exception('Unknown token');
    }

    private function createWordToken() : void
    {
        $value = $this->eatName();
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
            case 'on':
                $this->token = new Token(TokenType::ON);

                return;
            default:
                $this->token = new Token(TokenType::NAME, $value);

                return;
        }
    }

    private function createNumericToken() : void
    {
        $numberVal = $this->eatInt(true, false);

        if ($this->source->hasChar() && \in_array($this->source->getChar(), ['.', 'e', 'E'], true)) {
            if ($this->source->getChar() === '.') {
                $this->source->next();
                $numberVal .= '.' . $this->eatInt(false, true);
            }

            if ($this->source->hasChar() && \in_array($this->source->getChar(), ['e', 'E'], true)) {
                $this->source->next();
                $numberVal .= 'e' . $this->eatInt(true, true);
            }

            $this->token = new Token(TokenType::FLOAT, $numberVal);
        } else {
            $this->token = new Token(TokenType::INT, $numberVal);
        }

        if ($this->source->hasChar() && \ctype_alpha($this->source->getChar())) {
            throw new \Exception('Invalid number literal');
        }
    }

    private function skipWhiteSpace() : void
    {
        $this->eatChars(static function (string $char) : bool { return $char !== \PHP_EOL && \ctype_space($char); });
    }

    private function eatComment() : string
    {
        return $this->eatChars(static function (string $char) : bool {return $char !== \PHP_EOL; });
    }

    private function eatString() : string
    {
        $value = '';

        while ($this->source->hasChar()) {
            $char = $this->source->getChar();
            $this->source->next();

            switch ($char) {
                case \PHP_EOL:
                    throw new \Exception('Use block string literal instead');
                case '"':
                    return $value;
                case '\\':
                    $value .= $this->eatEscapeChar();

                    continue 2;
                default:
                    $value .= $char;
            }
        }

        throw new \Exception('String literal has no end.');
    }

    private function eatBlockString() : string
    {
        $value = '';

        while ($this->source->hasChar()) {
            switch ($this->source->getChar()) {
                case '"':
                    $quotes = $this->eatChars(static function (string $char) : bool { return $char === '"'; });

                    if (\strlen($quotes) === 3) {
                        return $this->formatBlockString($value);
                    }

                    if (\strlen($quotes) > 3) {
                        throw new \Exception('Invalid block string escape sequence');
                    }

                    $value .= $quotes;

                    continue 2;
                case '\\':
                    $this->source->next();
                    $quotes = $this->eatChars(static function (string $char) : bool { return $char === '"'; }, 3);

                    if (\strlen($quotes) === 3) {
                        $value .= '"""';
                    } else {
                        $value .= '\\' . $quotes;
                    }

                    continue 2;
                default:
                    $value .= $this->source->getChar();
                    $this->source->next();
            }
        }

        throw new \Exception('String literal has no end');
    }

    private function formatBlockString(string $value) : string
    {
        $lines = \explode(\PHP_EOL, $value);
        $lineCount = \count($lines);

        while ($lineCount > 0) {
            $first = \array_key_first($lines);

            if ($lines[$first] === '' || \ctype_space($lines[$first])) {
                unset($lines[$first]);
                --$lineCount;

                continue;
            }

            $last = \array_key_last($lines);

            if ($lines[$last] === '' || \ctype_space($lines[$last])) {
                unset($lines[$last]);
                --$lineCount;

                continue;
            }

            break;
        }

        $commonWhitespace = null;

        foreach ($lines as $line) {
            $trim = \ltrim($line);

            if ($trim === '') {
                continue;
            }

            $whitespaceCount = \strlen($line) - \strlen($trim);

            if ($commonWhitespace === null || $commonWhitespace > $whitespaceCount) {
                $commonWhitespace = $whitespaceCount;
            }
        }

        if (\in_array($commonWhitespace, [0, null], true)) {
            return \implode(\PHP_EOL, $lines);
        }

        $formattedLines = [];

        foreach ($lines as $line) {
            $formattedLines[] = \substr($line, $commonWhitespace);
        }

        return \implode(\PHP_EOL, $formattedLines);
    }

    private function eatEscapeChar() : string
    {
        $escapedChar = $this->source->getChar();

        if ($escapedChar === 'u') {
            $this->source->next();
            $hexdec = $this->eatChars(static function (string $char) : bool { return \ctype_xdigit($char); }, 4);

            if (\strlen($hexdec) !== 4) {
                throw new \Exception('Invalid unicode sequence');
            }

            return \mb_chr(\hexdec($hexdec), 'utf8');
        }

        $this->source->next();

        if (!\array_key_exists($escapedChar, self::ESCAPE_MAP)) {
            throw new \Exception('Unknown escape character.');
        }

        return self::ESCAPE_MAP[$escapedChar];
    }

    private function eatInt(bool $negative = true, bool $leadingZeros = true) : string
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

        $value .=  $this->eatChars(static function (string $char) : bool { return \ctype_digit($char); });

        if (!\is_numeric($value)) {
            throw new \Exception('Invalid numeric value');
        }

        return $value;
    }

    private function eatName() : string
    {
        return $this->eatChars(static function (string $char) : bool { return $char === '_' || \ctype_alnum($char); });
    }

    private function eatChars(callable $condition, int $limit = \PHP_INT_MAX) : string
    {
        $value = '';
        $count = 0;

        for (; $this->source->hasChar() && $count < $limit; $this->source->next()) {
            if ($condition($this->source->getChar()) === true) {
                $value .= $this->source->getChar();
                ++$count;

                continue;
            }

            break;
        }

        return $value;
    }
}
