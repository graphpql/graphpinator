<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Tokenizer;

use Graphpinator\Tokenizer\TokenType;

final class TokenContainerTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $source = new \Graphpinator\Source\StringSource('{}[]()');
        $tokenizer = new \Graphpinator\Tokenizer\TokenContainer($source);

        self::assertFalse($tokenizer->hasPrev());
        self::assertSame($tokenizer->getCurrent()->getType(), TokenType::CUR_O);
        self::assertSame($tokenizer->getNext()->getType(), TokenType::CUR_C);
        self::assertTrue($tokenizer->hasPrev());
        self::assertSame($tokenizer->getNext()->getType(), TokenType::SQU_O);
        self::assertTrue($tokenizer->hasPrev());
        self::assertSame($tokenizer->peekNext()->getType(), TokenType::SQU_C);
        self::assertSame($tokenizer->getNext()->getType(), TokenType::SQU_C);
        self::assertSame($tokenizer->getPrev()->getType(), TokenType::SQU_O);
        self::assertSame($tokenizer->assertNext(TokenType::SQU_C)->getType(), TokenType::SQU_C);
    }

    public function testIterator() : void
    {
        $source = new \Graphpinator\Source\StringSource('{}[]()');

        foreach (new \Graphpinator\Tokenizer\TokenContainer($source) as $token) {
            self::assertInstanceOf(\Graphpinator\Tokenizer\Token::class, $token);
        }
    }

    public function testInvalidPrev() : void
    {
        $this->expectException(\Graphpinator\Exception\Parser\UnexpectedEnd::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Parser\UnexpectedEnd::MESSAGE);

        $source = new \Graphpinator\Source\StringSource('{}[]()');
        $tokenizer = new \Graphpinator\Tokenizer\TokenContainer($source);
        $tokenizer->getPrev();
    }

    public function testInvalidNext() : void
    {
        $this->expectException(\Graphpinator\Exception\Parser\UnexpectedEnd::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Parser\UnexpectedEnd::MESSAGE);

        $source = new \Graphpinator\Source\StringSource('{');
        $tokenizer = new \Graphpinator\Tokenizer\TokenContainer($source);
        $tokenizer->getNext();
    }

    public function testInvalidPeek() : void
    {
        $this->expectException(\Graphpinator\Exception\Parser\UnexpectedEnd::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Parser\UnexpectedEnd::MESSAGE);

        $source = new \Graphpinator\Source\StringSource('{');
        $tokenizer = new \Graphpinator\Tokenizer\TokenContainer($source);
        $tokenizer->peekNext();
    }

    public function testInvalidAssert() : void
    {
        $this->expectException(\Graphpinator\Exception\Parser\UnexpectedEnd::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Parser\UnexpectedEnd::MESSAGE);

        $source = new \Graphpinator\Source\StringSource('{}[]()');
        $tokenizer = new \Graphpinator\Tokenizer\TokenContainer($source);
        $tokenizer->assertNext(TokenType::VARIABLE, \Graphpinator\Exception\Parser\UnexpectedEnd::class);
    }
}
