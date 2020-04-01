<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Tokenizer;

use Graphpinator\Tokenizer\TokenType;

final class TokenContainerTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $tokenizer = new \Graphpinator\Tokenizer\TokenContainer('{}[]()');

        self::assertFalse($tokenizer->hasPrev());
        self::assertSame($tokenizer->getCurrent()->getType(), TokenType::CUR_O);
        self::assertSame($tokenizer->getNext()->getType(), TokenType::CUR_C);
        self::assertSame($tokenizer->getNext()->getType(), TokenType::SQU_O);
        self::assertSame($tokenizer->peekNext()->getType(), TokenType::SQU_C);
        self::assertSame($tokenizer->getNext()->getType(), TokenType::SQU_C);
        self::assertSame($tokenizer->getPrev()->getType(), TokenType::SQU_O);
        self::assertSame($tokenizer->assertNext(TokenType::SQU_C)->getType(), TokenType::SQU_C);
    }

    public function testIterator() : void
    {
        foreach (new \Graphpinator\Tokenizer\TokenContainer('{}[]()') as $token) {
            self::assertInstanceOf(\Graphpinator\Tokenizer\Token::class, $token);
        }
    }

    public function testInvalidPrev() : void
    {
        $this->expectException(\Exception::class);

        $tokenizer = new \Graphpinator\Tokenizer\TokenContainer('{}[]()');
        $tokenizer->getPrev();
    }

    public function testInvalidNext() : void
    {
        $this->expectException(\Exception::class);

        $tokenizer = new \Graphpinator\Tokenizer\TokenContainer('}');
        $tokenizer->getNext();
    }

    public function testInvalidPeek() : void
    {
        $this->expectException(\Exception::class);

        $tokenizer = new \Graphpinator\Tokenizer\TokenContainer('{');
        $tokenizer->peekNext();
    }

    public function testInvalidAssert() : void
    {
        $this->expectException(\Exception::class);

        $tokenizer = new \Graphpinator\Tokenizer\TokenContainer('{}[]()');
        $tokenizer->assertNext(TokenType::VARIABLE);
    }
}
