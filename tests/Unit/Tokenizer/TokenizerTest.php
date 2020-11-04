<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Tokenizer;

use \Graphpinator\Tokenizer\Token;
use \Graphpinator\Tokenizer\TokenType;

final class TokenizerTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                '""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), ''),
                ],
            ],
            [
                '"ěščřžýá"',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), 'ěščřžýá'),
                ],
            ],
            [
                '"\\""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), '"'),
                ],
            ],
            [
                '"\\\\"',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), '\\'),
                ],
            ],
            [
                '"\\/"',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), '/'),
                ],
            ],
            [
                '"\\b"',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), "\u{0008}"),
                ],
            ],
            [
                '"\\f"',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), "\u{000C}"),
                ],
            ],
            [
                '"\\n"',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), "\u{000A}"),
                ],
            ],
            [
                '"\\r"',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), "\u{000D}"),
                ],
            ],
            [
                '"\\t"',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), "\u{0009}"),
                ],
            ],
            [
                '"\\u1234"',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), "\u{1234}"),
                ],
            ],
            [
                '"u1234"',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), 'u1234'),
                ],
            ],
            [
                '"abc\\u1234abc"',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), "abc\u{1234}abc"),
                ],
            ],
            [
                '"blabla\\t\\"\\nfoobar"',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), "blabla\u{0009}\"\u{000A}foobar"),
                ],
            ],
            [
                '""""""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), ''),
                ],
            ],
            [
                '""""""""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), ''),
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 7), ''),
                ],
            ],
            [
                '"""' . \PHP_EOL . '"""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), ''),
                ],
            ],
            [
                '"""   """',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), ''),
                ],
            ],
            [
                '"""  abc  """',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), 'abc  '),
                ],
            ],
            [
                '"""' . \PHP_EOL . \PHP_EOL . \PHP_EOL . '"""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), ''),
                ],
            ],
            [
                '"""' . \PHP_EOL . \PHP_EOL . 'foo' . \PHP_EOL . \PHP_EOL . '"""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), 'foo'),
                ],
            ],
            [
                '"""' . \PHP_EOL . \PHP_EOL . '       foo' . \PHP_EOL . \PHP_EOL . '"""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), 'foo'),
                ],
            ],
            [
                '"""' . \PHP_EOL . ' foo' . \PHP_EOL . '       foo' . \PHP_EOL . '"""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), 'foo' . \PHP_EOL . '      foo'),
                ],
            ],
            [
                '"""   foo' . \PHP_EOL . \PHP_EOL . '  foo' . \PHP_EOL . \PHP_EOL . ' foo"""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), '  foo' . \PHP_EOL . \PHP_EOL . ' foo' . \PHP_EOL . \PHP_EOL . 'foo'),
                ],
            ],
            [
                '"""\\n"""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), "\\n"),
                ],
            ],
            [
                '"""\\""""""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), '"""'),
                ],
            ],
            [
                '"""\\\\""""""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), '\\"""'),
                ],
            ],
            [
                '"""abc\\"""abc"""',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 1), 'abc"""abc'),
                ],
            ],
            [
                '0',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::INT, new \Graphpinator\Source\Location(1, 1), '0'),
                ],
            ],
            [
                '-0',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::INT, new \Graphpinator\Source\Location(1, 1), '-0'),
                ],
            ],
            [
                '4',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::INT, new \Graphpinator\Source\Location(1, 1), '4'),
                ],
            ],
            [
                '-4',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::INT, new \Graphpinator\Source\Location(1, 1), '-4'),
                ],
            ],
            [
                '4.0',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::FLOAT, new \Graphpinator\Source\Location(1, 1), '4.0'),
                ],
            ],
            [
                '-4.0',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::FLOAT, new \Graphpinator\Source\Location(1, 1), '-4.0'),
                ],
            ],
            [
                '4e10',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::FLOAT, new \Graphpinator\Source\Location(1, 1), '4e10'),
                ],
            ],
            [
                '4e0010',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::FLOAT, new \Graphpinator\Source\Location(1, 1), '4e0010'),
                ],
            ],
            [
                '-4e10',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::FLOAT, new \Graphpinator\Source\Location(1, 1), '-4e10'),
                ],
            ],
            [
                '4E10',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::FLOAT, new \Graphpinator\Source\Location(1, 1), '4e10'),
                ],
            ],
            [
                '-4e-10',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::FLOAT, new \Graphpinator\Source\Location(1, 1), '-4e-10'),
                ],
            ],
            [
                '4e+10',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::FLOAT, new \Graphpinator\Source\Location(1, 1), '4e10'),
                ],
            ],
            [
                '-4e+10',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::FLOAT, new \Graphpinator\Source\Location(1, 1), '-4e10'),
                ],
            ],
            [
                'null',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NULL, new \Graphpinator\Source\Location(1, 1)),
                ],
            ],
            [
                'NULL',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NULL, new \Graphpinator\Source\Location(1, 1)),
                ],
            ],
            [
                'Name',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 1), 'Name'),
                ],
            ],
            [
                'NAME',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 1), 'NAME'),
                ],
            ],
            [
                '__Name',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 1), '__Name'),
                ],
            ],
            [
                'Name_with_underscore',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 1), 'Name_with_underscore'),
                ],
            ],
            [
                'FALSE true',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::FALSE, new \Graphpinator\Source\Location(1, 1)),
                    new \Graphpinator\Tokenizer\Token(TokenType::TRUE, new \Graphpinator\Source\Location(1, 7)),
                ],
            ],
            [
                '... type fragment',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::ELLIP, new \Graphpinator\Source\Location(1, 1)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 5), 'type'),
                    new \Graphpinator\Tokenizer\Token(TokenType::FRAGMENT, new \Graphpinator\Source\Location(1, 10)),
                ],
            ],
            [
                '-4.024E-10',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::FLOAT, new \Graphpinator\Source\Location(1, 1), '-4.024e-10'),
                ],
            ],
            [
                'query { field1 { innerField } }',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 1), 'query'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(1, 7)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 9), 'field1'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(1, 16)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 18), 'innerField'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(1, 29)),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(1, 31)),
                ],
            ],
            [
                'mutation { field(argName: 4) }',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 1), 'mutation'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(1, 10)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 12), 'field'),
                    new \Graphpinator\Tokenizer\Token(TokenType::PAR_O, new \Graphpinator\Source\Location(1, 17)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 18), 'argName'),
                    new \Graphpinator\Tokenizer\Token(TokenType::COLON, new \Graphpinator\Source\Location(1, 25)),
                    new \Graphpinator\Tokenizer\Token(TokenType::INT, new \Graphpinator\Source\Location(1, 27), '4'),
                    new \Graphpinator\Tokenizer\Token(TokenType::PAR_C, new \Graphpinator\Source\Location(1, 28)),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(1, 30)),
                ],
            ],
            [
                'subscription { field(argName: "str") }',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 1), 'subscription'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(1, 14)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 16), 'field'),
                    new \Graphpinator\Tokenizer\Token(TokenType::PAR_O, new \Graphpinator\Source\Location(1, 21)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 22), 'argName'),
                    new \Graphpinator\Tokenizer\Token(TokenType::COLON, new \Graphpinator\Source\Location(1, 29)),
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 31), 'str'),
                    new \Graphpinator\Tokenizer\Token(TokenType::PAR_C, new \Graphpinator\Source\Location(1, 36)),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(1, 38)),
                ],
            ],
            [
                'query { field(argName: ["str", "str", $varName]) @directiveName }',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 1), 'query'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(1, 7)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 9), 'field'),
                    new \Graphpinator\Tokenizer\Token(TokenType::PAR_O, new \Graphpinator\Source\Location(1, 14)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 15), 'argName'),
                    new \Graphpinator\Tokenizer\Token(TokenType::COLON, new \Graphpinator\Source\Location(1, 22)),
                    new \Graphpinator\Tokenizer\Token(TokenType::SQU_O, new \Graphpinator\Source\Location(1, 24)),
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 25), 'str'),
                    new \Graphpinator\Tokenizer\Token(TokenType::COMMA, new \Graphpinator\Source\Location(1, 30)),
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 32), 'str'),
                    new \Graphpinator\Tokenizer\Token(TokenType::COMMA, new \Graphpinator\Source\Location(1, 37)),
                    new \Graphpinator\Tokenizer\Token(TokenType::VARIABLE, new \Graphpinator\Source\Location(1, 39), 'varName'),
                    new \Graphpinator\Tokenizer\Token(TokenType::SQU_C, new \Graphpinator\Source\Location(1, 47)),
                    new \Graphpinator\Tokenizer\Token(TokenType::PAR_C, new \Graphpinator\Source\Location(1, 48)),
                    new \Graphpinator\Tokenizer\Token(TokenType::DIRECTIVE, new \Graphpinator\Source\Location(1, 50), 'directiveName'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(1, 65)),
                ],
            ],
            [
                'query {' . \PHP_EOL .
                    'field1 {' . \PHP_EOL .
                        'innerField' . \PHP_EOL .
                    '}' . \PHP_EOL .
                '}',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 1), 'query'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(1, 7)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NEWLINE, new \Graphpinator\Source\Location(1, 8)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(2, 1), 'field1'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(2, 8)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NEWLINE, new \Graphpinator\Source\Location(2, 9)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(3, 1), 'innerField'),
                    new \Graphpinator\Tokenizer\Token(TokenType::NEWLINE, new \Graphpinator\Source\Location(3, 11)),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(4, 1)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NEWLINE, new \Graphpinator\Source\Location(4, 2)),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(5, 1)),
                ],
            ],
            [
                'query {' . \PHP_EOL .
                    'field1 {' . \PHP_EOL .
                        '# this is comment' . \PHP_EOL .
                        'innerField' . \PHP_EOL .
                    '}' . \PHP_EOL .
                '}',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 1), 'query'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(1, 7)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NEWLINE, new \Graphpinator\Source\Location(1, 8)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(2, 1), 'field1'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(2, 8)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NEWLINE, new \Graphpinator\Source\Location(2, 9)),
                    new \Graphpinator\Tokenizer\Token(TokenType::COMMENT, new \Graphpinator\Source\Location(3, 1), ' this is comment'),
                    new \Graphpinator\Tokenizer\Token(TokenType::NEWLINE, new \Graphpinator\Source\Location(3, 18)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(4, 1), 'innerField'),
                    new \Graphpinator\Tokenizer\Token(TokenType::NEWLINE, new \Graphpinator\Source\Location(4, 11)),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(5, 1)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NEWLINE, new \Graphpinator\Source\Location(5, 2)),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(6, 1)),
                ],
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param string $source
     * @param array $tokens
     */
    public function testSimple(string $source, array $tokens) : void
    {
        $source = new \Graphpinator\Source\StringSource($source);
        $tokenizer = new \Graphpinator\Tokenizer\Tokenizer($source, false);
        $index = 0;

        foreach ($tokenizer as $token) {
            self::assertSame($tokens[$index]->getType(), $token->getType());
            self::assertSame($tokens[$index]->getValue(), $token->getValue());
            self::assertSame($tokens[$index]->getLocation()->getLine(), $token->getLocation()->getLine());
            self::assertSame($tokens[$index]->getLocation()->getColumn(), $token->getLocation()->getColumn());
            ++$index;
        }
    }

    public function skipDataProvider() : array
    {
        return [
            [
                'query { field(argName: ["str", "str", true, false, null]) }',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 1), 'query'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(1, 7)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 9), 'field'),
                    new \Graphpinator\Tokenizer\Token(TokenType::PAR_O, new \Graphpinator\Source\Location(1, 14)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 15), 'argName'),
                    new \Graphpinator\Tokenizer\Token(TokenType::COLON, new \Graphpinator\Source\Location(1, 22)),
                    new \Graphpinator\Tokenizer\Token(TokenType::SQU_O, new \Graphpinator\Source\Location(1, 24)),
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 25), 'str'),
                    new \Graphpinator\Tokenizer\Token(TokenType::STRING, new \Graphpinator\Source\Location(1, 32), 'str'),
                    new \Graphpinator\Tokenizer\Token(TokenType::TRUE, new \Graphpinator\Source\Location(1, 39)),
                    new \Graphpinator\Tokenizer\Token(TokenType::FALSE, new \Graphpinator\Source\Location(1, 45)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NULL, new \Graphpinator\Source\Location(1, 52)),
                    new \Graphpinator\Tokenizer\Token(TokenType::SQU_C, new \Graphpinator\Source\Location(1, 56)),
                    new \Graphpinator\Tokenizer\Token(TokenType::PAR_C, new \Graphpinator\Source\Location(1, 57)),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(1, 59)),
                ],
            ],
            [
                'query {' . \PHP_EOL .
                    'field1 {' . \PHP_EOL .
                    'innerField' . \PHP_EOL .
                    '}' . \PHP_EOL .
                '}',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 1), 'query'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(1, 7)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(2, 1), 'field1'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(2, 8)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(3, 1), 'innerField'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(4, 1)),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(5, 1)),
                ],
            ],
            [
                'query {' . \PHP_EOL .
                    'field1 {' . \PHP_EOL .
                        '# this is comment' . \PHP_EOL .
                        'innerField' . \PHP_EOL .
                    '}' . \PHP_EOL .
                '}',
                [
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(1, 1), 'query'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(1, 7)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(2, 1), 'field1'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_O, new \Graphpinator\Source\Location(2, 8)),
                    new \Graphpinator\Tokenizer\Token(TokenType::NAME, new \Graphpinator\Source\Location(4, 1), 'innerField'),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(5, 1)),
                    new \Graphpinator\Tokenizer\Token(TokenType::CUR_C, new \Graphpinator\Source\Location(6, 1)),
                ],
            ],
        ];
    }

    /**
     * @dataProvider skipDataProvider
     * @param string $source
     * @param array $tokens
     */
    public function testSkip(string $source, array $tokens) : void
    {
        $source = new \Graphpinator\Source\StringSource($source);
        $tokenizer = new \Graphpinator\Tokenizer\Tokenizer($source);
        $index = 0;

        foreach ($tokenizer as $token) {
            self::assertSame($tokens[$index]->getType(), $token->getType());
            self::assertSame($tokens[$index]->getValue(), $token->getValue());
            self::assertSame($tokens[$index]->getLocation()->getLine(), $token->getLocation()->getLine());
            self::assertSame($tokens[$index]->getLocation()->getColumn(), $token->getLocation()->getColumn());
            ++$index;
        }
    }

    public function invalidDataProvider() : array
    {
        return [
            ['"foo', \Graphpinator\Exception\Tokenizer\StringLiteralWithoutEnd::class],
            ['""""', \Graphpinator\Exception\Tokenizer\StringLiteralWithoutEnd::class],
            ['"""""', \Graphpinator\Exception\Tokenizer\StringLiteralWithoutEnd::class],
            ['"""""""', \Graphpinator\Exception\Tokenizer\StringLiteralWithoutEnd::class],
            ['"""\\""""', \Graphpinator\Exception\Tokenizer\StringLiteralWithoutEnd::class],
            ['"""abc""""', \Graphpinator\Exception\Tokenizer\StringLiteralWithoutEnd::class],
            ['"\\1"', \Graphpinator\Exception\Tokenizer\StringLiteralInvalidEscape::class],
            ['"\\u12z3"', \Graphpinator\Exception\Tokenizer\StringLiteralInvalidEscape::class],
            ['"\\u123"', \Graphpinator\Exception\Tokenizer\StringLiteralInvalidEscape::class],
            ['"' . \PHP_EOL . '"', \Graphpinator\Exception\Tokenizer\StringLiteralNewLine::class],
            ['123.-1', \Graphpinator\Exception\Tokenizer\NumericLiteralNegativeFraction::class],
            ['- 123', \Graphpinator\Exception\Tokenizer\NumericLiteralMalformed::class],
            ['123. ', \Graphpinator\Exception\Tokenizer\NumericLiteralMalformed::class],
            ['123.1e ', \Graphpinator\Exception\Tokenizer\NumericLiteralMalformed::class],
            ['00123', \Graphpinator\Exception\Tokenizer\NumericLiteralLeadingZero::class],
            ['00123.123', \Graphpinator\Exception\Tokenizer\NumericLiteralLeadingZero::class],
            ['123.1E ', \Graphpinator\Exception\Tokenizer\NumericLiteralMalformed::class],
            ['123e ', \Graphpinator\Exception\Tokenizer\NumericLiteralMalformed::class],
            ['123E ', \Graphpinator\Exception\Tokenizer\NumericLiteralMalformed::class],
            ['123Name', \Graphpinator\Exception\Tokenizer\NumericLiteralFollowedByName::class],
            ['123.123Name', \Graphpinator\Exception\Tokenizer\NumericLiteralFollowedByName::class],
            ['123.123eName', \Graphpinator\Exception\Tokenizer\NumericLiteralMalformed::class],
            ['-.E', \Graphpinator\Exception\Tokenizer\NumericLiteralMalformed::class],
            ['>>', \Graphpinator\Exception\Tokenizer\UnknownSymbol::class],
            ['123.45.67', \Graphpinator\Exception\Tokenizer\InvalidEllipsis::class],
            ['.E', \Graphpinator\Exception\Tokenizer\InvalidEllipsis::class],
            ['..', \Graphpinator\Exception\Tokenizer\InvalidEllipsis::class],
            ['....', \Graphpinator\Exception\Tokenizer\InvalidEllipsis::class],
            ['@ directiveName', \Graphpinator\Exception\Tokenizer\MissingDirectiveName::class],
            ['$ variableName', \Graphpinator\Exception\Tokenizer\MissingVariableName::class],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param string $source
     * @param string $exception
     */
    public function testInvalid(string $source, string $exception) : void
    {
        $this->expectException($exception);
        $this->expectExceptionMessage(\constant($exception . '::MESSAGE'));

        $source = new \Graphpinator\Source\StringSource($source);
        $tokenizer = new \Graphpinator\Tokenizer\Tokenizer($source);

        foreach ($tokenizer as $token) {
            self::assertInstanceOf(Token::class, $token);
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

        $index = 0;

        foreach ($tokenizer as $key => $token) {
            self::assertSame($indexes[$index], $key);
            ++$index;
        }
    }

    public function testBlockStringIndent() : void
    {
        $source1 = new \Graphpinator\Source\StringSource('"""' . \PHP_EOL .
            '    Hello,' . \PHP_EOL .
            '      World!' . \PHP_EOL .
            \PHP_EOL .
            '    Yours,' . \PHP_EOL .
            '      GraphQL.' . \PHP_EOL .
            '"""');
        $source2 = new \Graphpinator\Source\StringSource('"Hello,\\n  World!\\n\\nYours,\\n  GraphQL."');

        $tokenizer = new \Graphpinator\Tokenizer\Tokenizer($source1);
        $tokenizer->rewind();
        $token1 = $tokenizer->current();
        $tokenizer = new \Graphpinator\Tokenizer\Tokenizer($source2);
        $tokenizer->rewind();
        $token2 = $tokenizer->current();

        self::assertSame($token1->getType(), $token2->getType());
        self::assertSame($token1->getValue(), $token2->getValue());
    }

    public function testRewind() : void
    {
        $source = new \Graphpinator\Source\StringSource('Hello"World"');

        $tokenizer = new \Graphpinator\Tokenizer\Tokenizer($source, false);
        $tokenizer->rewind();
        self::assertSame('Hello', $tokenizer->current()->getValue());
        $tokenizer->next();
        self::assertSame('World', $tokenizer->current()->getValue());
        $tokenizer->rewind();
        self::assertSame('Hello', $tokenizer->current()->getValue());
    }
}
