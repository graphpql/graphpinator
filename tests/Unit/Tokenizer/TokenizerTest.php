<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Tokenizer;

use Graphpinator\Tokenizer\Token;
use Graphpinator\Tokenizer\TokenType;

final class TokenizerTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['""', [
                new Token(TokenType::STRING, ''),
            ]],
            ['"ěščřžýá"', [
                new Token(TokenType::STRING, 'ěščřžýá'),
            ]],
            ['"\\""', [
                new Token(TokenType::STRING, '"'),
            ]],
            ['"\\\\"', [
                new Token(TokenType::STRING, '\\'),
            ]],
            ['"\\/"', [
                new Token(TokenType::STRING, '/'),
            ]],
            ['"\\b"', [
                new Token(TokenType::STRING, "\u{0008}"),
            ]],
            ['"\\f"', [
                new Token(TokenType::STRING, "\u{000C}"),
            ]],
            ['"\\n"', [
                new Token(TokenType::STRING, "\u{000A}"),
            ]],
            ['"\\r"', [
                new Token(TokenType::STRING, "\u{000D}"),
            ]],
            ['"\\t"', [
                new Token(TokenType::STRING, "\u{0009}"),
            ]],
            ['"blabla\\t\\"\\nfoobar"', [
                new Token(TokenType::STRING, "blabla\u{0009}\"\u{000A}foobar"),
            ]],
            ['4', [
                new Token(TokenType::INT, '4'),
            ]],
            ['-4', [
                new Token(TokenType::INT, '-4'),
            ]],
            ['4.0', [
                new Token(TokenType::FLOAT, '4.0'),
            ]],
            ['-4.0', [
                new Token(TokenType::FLOAT, '-4.0'),
            ]],
            ['4e10', [
                new Token(TokenType::FLOAT, '4e10'),
            ]],
            ['-4e10', [
                new Token(TokenType::FLOAT, '-4e10'),
            ]],
            ['4E10', [
                new Token(TokenType::FLOAT, '4e10'),
            ]],
            ['-4e-10', [
                new Token(TokenType::FLOAT, '-4e-10'),
            ]],
            ['null', [
                new Token(TokenType::NULL),
            ]],
            ['NULL', [
                new Token(TokenType::NULL),
            ]],
            ['Name', [
                new Token(TokenType::NAME, 'Name'),
            ]],
            ['NAME', [
                new Token(TokenType::NAME, 'NAME'),
            ]],
            ['__Name', [
                new Token(TokenType::NAME, '__Name'),
            ]],
            ['FALSE true', [
                new Token(TokenType::FALSE),
                new Token(TokenType::TRUE),
            ]],
            ['... type fragment', [
                new Token(TokenType::ELLIP),
                new Token(TokenType::NAME, 'type'),
                new Token(TokenType::FRAGMENT),
            ]],
            ['-4.024E-10', [
                new Token(TokenType::FLOAT, '-4.024e-10'),
            ]],
            ['query { field1 { innerField } }', [
                new Token(TokenType::NAME, 'query'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NAME, 'field1'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NAME, 'innerField'),
                new Token(TokenType::CUR_C),
                new Token(TokenType::CUR_C),
            ]],
            ['mutation { field(argName: 4) }', [
                new Token(TokenType::NAME, 'mutation'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NAME, 'field'),
                new Token(TokenType::PAR_O),
                new Token(TokenType::NAME, 'argName'),
                new Token(TokenType::COLON),
                new Token(TokenType::INT, '4'),
                new Token(TokenType::PAR_C),
                new Token(TokenType::CUR_C),
            ]],
            ['subscription { field(argName: "str") }', [
                new Token(TokenType::NAME, 'subscription'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NAME, 'field'),
                new Token(TokenType::PAR_O),
                new Token(TokenType::NAME, 'argName'),
                new Token(TokenType::COLON),
                new Token(TokenType::STRING, 'str'),
                new Token(TokenType::PAR_C),
                new Token(TokenType::CUR_C),
            ]],
            ['query { field(argName: ["str", "str", $varName]) @directiveName }', [
                new Token(TokenType::NAME, 'query'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NAME, 'field'),
                new Token(TokenType::PAR_O),
                new Token(TokenType::NAME, 'argName'),
                new Token(TokenType::COLON),
                new Token(TokenType::SQU_O),
                new Token(TokenType::STRING, 'str'),
                new Token(TokenType::COMMA),
                new Token(TokenType::STRING, 'str'),
                new Token(TokenType::COMMA),
                new Token(TokenType::VARIABLE, 'varName'),
                new Token(TokenType::SQU_C),
                new Token(TokenType::PAR_C),
                new Token(TokenType::DIRECTIVE, 'directiveName'),
                new Token(TokenType::CUR_C),
            ]],
            ['query {' . \PHP_EOL .
                'field1 {' . \PHP_EOL .
                    'innerField' . \PHP_EOL .
                '}' . \PHP_EOL .
            '}', [
                new Token(TokenType::NAME, 'query'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NEWLINE),
                new Token(TokenType::NAME, 'field1'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NEWLINE),
                new Token(TokenType::NAME, 'innerField'),
                new Token(TokenType::NEWLINE),
                new Token(TokenType::CUR_C),
                new Token(TokenType::NEWLINE),
                new Token(TokenType::CUR_C),
            ]],
            ['query {' . \PHP_EOL .
                'field1 {' . \PHP_EOL .
                    '# this is comment' . \PHP_EOL .
                    'innerField' . \PHP_EOL .
                '}' . \PHP_EOL .
            '}', [
                new Token(TokenType::NAME, 'query'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NEWLINE),
                new Token(TokenType::NAME, 'field1'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NEWLINE),
                new Token(TokenType::COMMENT, ' this is comment'),
                new Token(TokenType::NEWLINE),
                new Token(TokenType::NAME, 'innerField'),
                new Token(TokenType::NEWLINE),
                new Token(TokenType::CUR_C),
                new Token(TokenType::NEWLINE),
                new Token(TokenType::CUR_C),
            ]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testSimple(string $source, array $tokens) : void
    {
        $source = new \Graphpinator\Source\StringSource($source);
        $tokenizer = new \Graphpinator\Tokenizer\Tokenizer($source, false);
        $index = 0;

        foreach ($tokenizer as $token) {
            self::assertSame($tokens[$index]->getType(), $token->getType());
            self::assertSame($tokens[$index]->getValue(), $token->getValue());
            ++$index;
        }
    }

    public function skipDataProvider() : array
    {
        return [
            ['query { field(argName: ["str", "str", true, false, null]) }', [
                new Token(TokenType::NAME, 'query'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NAME, 'field'),
                new Token(TokenType::PAR_O),
                new Token(TokenType::NAME, 'argName'),
                new Token(TokenType::COLON),
                new Token(TokenType::SQU_O),
                new Token(TokenType::STRING, 'str'),
                new Token(TokenType::STRING, 'str'),
                new Token(TokenType::TRUE),
                new Token(TokenType::FALSE),
                new Token(TokenType::NULL),
                new Token(TokenType::SQU_C),
                new Token(TokenType::PAR_C),
                new Token(TokenType::CUR_C),
            ]],
            ['query {' . \PHP_EOL .
                'field1 {' . \PHP_EOL .
                'innerField' . \PHP_EOL .
                '}' . \PHP_EOL .
            '}', [
                new Token(TokenType::NAME, 'query'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NAME, 'field1'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NAME, 'innerField'),
                new Token(TokenType::CUR_C),
                new Token(TokenType::CUR_C),
            ]],
            ['query {' . \PHP_EOL .
                'field1 {' . \PHP_EOL .
                    '# this is comment' . \PHP_EOL .
                    'innerField' . \PHP_EOL .
                '}' . \PHP_EOL .
            '}', [
                new Token(TokenType::NAME, 'query'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NAME, 'field1'),
                new Token(TokenType::CUR_O),
                new Token(TokenType::NAME, 'innerField'),
                new Token(TokenType::CUR_C),
                new Token(TokenType::CUR_C),
            ]],
        ];
    }

    /**
     * @dataProvider skipDataProvider
     */
    public function testSkip(string $source, array $tokens) : void
    {
        $source = new \Graphpinator\Source\StringSource($source);
        $tokenizer = new \Graphpinator\Tokenizer\Tokenizer($source);
        $index = 0;

        foreach ($tokenizer as $token) {
            self::assertSame($tokens[$index]->getType(), $token->getType());
            self::assertSame($tokens[$index]->getValue(), $token->getValue());
            ++$index;
        }
    }

    public function invalidDataProvider() : array
    {
        return [
            ['"""""'],
            ['"\\1"'],
            ['"' . \PHP_EOL . '"'],
            ['- 123'],
            ['123.'],
            ['123.1e'],
            ['123.-1'],
            ['00123'],
            ['00123.123'],
            ['123.1E'],
            ['123.45.67'],
            ['123e'],
            ['123E'],
            ['123Name'],
            ['123.123Name'],
            ['123.1eName'],
            ['.E'],
            ['-.E'],
            ['>>'],
            ['..'],
            ['....'],
            ['@ directiveName'],
            ['$ variableName'],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalid(string $source) : void
    {
        $this->expectException(\Exception::class);

        $source = new \Graphpinator\Source\StringSource($source);
        $tokenizer = new \Graphpinator\Tokenizer\Tokenizer($source);

        foreach ($tokenizer as $token) {
        }
    }

    public function testSourceIndex() : void
    {
        $source = new \Graphpinator\Source\StringSource('query { "ěščřžýá" }');
        $tokenizer = new \Graphpinator\Tokenizer\Tokenizer($source);
        $indexes = [0, 6, 8, 18];
        $index = 0;

        foreach ($tokenizer as $key => $token) {
            self::assertSame($indexes[$index], $key);
            ++$index;
        }
    }
}
