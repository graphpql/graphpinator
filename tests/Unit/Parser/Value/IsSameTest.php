<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Parser\Value;

final class IsSameTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [new \Graphpinator\Parser\Value\Literal(123), new \Graphpinator\Parser\Value\Literal(123), true],
            [new \Graphpinator\Parser\Value\Literal(123), new \Graphpinator\Parser\Value\Literal(456), false],
            [new \Graphpinator\Parser\Value\Literal(123.123), new \Graphpinator\Parser\Value\Literal(123.123), true],
            [new \Graphpinator\Parser\Value\Literal(123.123), new \Graphpinator\Parser\Value\Literal(123.456), false],
            [new \Graphpinator\Parser\Value\Literal('123'), new \Graphpinator\Parser\Value\Literal('123'), true],
            [new \Graphpinator\Parser\Value\Literal('123'), new \Graphpinator\Parser\Value\Literal('abc'), false],
            [new \Graphpinator\Parser\Value\Literal(true), new \Graphpinator\Parser\Value\Literal(true), true],
            [new \Graphpinator\Parser\Value\Literal(true), new \Graphpinator\Parser\Value\Literal(false), false],
            [new \Graphpinator\Parser\Value\ListVal([]), new \Graphpinator\Parser\Value\ListVal([]), true],
            [
        new \Graphpinator\Parser\Value\ListVal([]),
        new \Graphpinator\Parser\Value\ListVal([
                new \Graphpinator\Parser\Value\Literal('123'),
                new \Graphpinator\Parser\Value\Literal('abc'),
            ]),
        false,
                ],
            [
            new \Graphpinator\Parser\Value\ListVal([
                new \Graphpinator\Parser\Value\Literal('123'),
                new \Graphpinator\Parser\Value\Literal('abc'),
            ]),
        new \Graphpinator\Parser\Value\ListVal([
                new \Graphpinator\Parser\Value\Literal('123'),
                new \Graphpinator\Parser\Value\Literal('abc'),
            ]),
        true,
                ],
            [
            new \Graphpinator\Parser\Value\ListVal([
                new \Graphpinator\Parser\Value\Literal('123'),
                new \Graphpinator\Parser\Value\Literal('abc'),
            ]),
        new \Graphpinator\Parser\Value\ListVal([
                new \Graphpinator\Parser\Value\Literal('123'),
            ]),
        false,
                ],
            [
            new \Graphpinator\Parser\Value\ListVal([
                new \Graphpinator\Parser\Value\Literal('abc'),
            ]),
        new \Graphpinator\Parser\Value\ListVal([
                new \Graphpinator\Parser\Value\Literal('123'),
            ]),
        false,
                ],
            [new \Graphpinator\Parser\Value\ObjectVal([]), new \Graphpinator\Parser\Value\ObjectVal([]), true],
            [
            new \Graphpinator\Parser\Value\ObjectVal([]),
            new \Graphpinator\Parser\Value\ObjectVal([
                'val1' => new \Graphpinator\Parser\Value\Literal('123'),
                'val2' => new \Graphpinator\Parser\Value\Literal('abc'),
            ]),
        false,
                ],
            [
            new \Graphpinator\Parser\Value\ObjectVal([
                'val1' => new \Graphpinator\Parser\Value\Literal('123'),
                'val2' => new \Graphpinator\Parser\Value\Literal('abc'),
            ]),
        new \Graphpinator\Parser\Value\ObjectVal([
                'val1' => new \Graphpinator\Parser\Value\Literal('123'),
                'val2' => new \Graphpinator\Parser\Value\Literal('abc'),
            ]),
        true,
                ],
            [
            new \Graphpinator\Parser\Value\ObjectVal([
                'val1' => new \Graphpinator\Parser\Value\Literal('123'),
                'val2' => new \Graphpinator\Parser\Value\Literal('abc'),
            ]),
        new \Graphpinator\Parser\Value\ObjectVal([
                'val1' => new \Graphpinator\Parser\Value\Literal('123'),
                'val3' => new \Graphpinator\Parser\Value\Literal('abc'),
            ]),
        false,
                ],
            [
            new \Graphpinator\Parser\Value\ObjectVal([
                'val1' => new \Graphpinator\Parser\Value\Literal('123'),
                'val2' => new \Graphpinator\Parser\Value\Literal('abc'),
            ]),
        new \Graphpinator\Parser\Value\ObjectVal([
                'val1' => new \Graphpinator\Parser\Value\Literal('123'),
                'val2' => new \Graphpinator\Parser\Value\Literal('456'),
            ]),
        false,
                ],
            [new \Graphpinator\Parser\Value\VariableRef('var1'), new \Graphpinator\Parser\Value\VariableRef('var1'), true],
            [new \Graphpinator\Parser\Value\VariableRef('var1'), new \Graphpinator\Parser\Value\VariableRef('var2'), false],
            [new \Graphpinator\Parser\Value\Literal(123), new \Graphpinator\Parser\Value\Literal(123.123), false],
            [new \Graphpinator\Parser\Value\Literal(123), new \Graphpinator\Parser\Value\Literal('123'), false],
            [new \Graphpinator\Parser\Value\Literal(123), new \Graphpinator\Parser\Value\Literal(true), false],
            [new \Graphpinator\Parser\Value\Literal(123), new \Graphpinator\Parser\Value\Literal(false), false],
            [
            new \Graphpinator\Parser\Value\Literal(123),
            new \Graphpinator\Parser\Value\ListVal([
                new \Graphpinator\Parser\Value\Literal(123),
            ]),
        false,
                ],
            [
            new \Graphpinator\Parser\Value\Literal(123),
            new \Graphpinator\Parser\Value\ObjectVal([
                'val' => new \Graphpinator\Parser\Value\Literal(123),
            ]),
        false,
                ],
            [new \Graphpinator\Parser\Value\Literal(123), new \Graphpinator\Parser\Value\VariableRef('var1'), false],
            [new \Graphpinator\Parser\Value\Literal(123.123), new \Graphpinator\Parser\Value\Literal('123.123'), false],
            [new \Graphpinator\Parser\Value\Literal(123.123), new \Graphpinator\Parser\Value\Literal(true), false],
            [new \Graphpinator\Parser\Value\Literal(123.123), new \Graphpinator\Parser\Value\Literal(false), false],
            [
            new \Graphpinator\Parser\Value\Literal(123.123),
            new \Graphpinator\Parser\Value\ListVal([
                new \Graphpinator\Parser\Value\Literal(123.123),
            ]),
        false,
                ],
            [
            new \Graphpinator\Parser\Value\Literal(123.123),
            new \Graphpinator\Parser\Value\ObjectVal([
                'val' => new \Graphpinator\Parser\Value\Literal(123.123),
            ]),
        false,
                ],
            [new \Graphpinator\Parser\Value\Literal(123.123), new \Graphpinator\Parser\Value\VariableRef('var1'), false],
            [new \Graphpinator\Parser\Value\Literal('123'), new \Graphpinator\Parser\Value\Literal(true), false],
            [new \Graphpinator\Parser\Value\Literal('123'), new \Graphpinator\Parser\Value\Literal(false), false],
            [
            new \Graphpinator\Parser\Value\Literal('123'),
            new \Graphpinator\Parser\Value\ListVal([
                new \Graphpinator\Parser\Value\Literal('123'),
            ]),
        false,
                ],
            [
            new \Graphpinator\Parser\Value\Literal('123'),
            new \Graphpinator\Parser\Value\ObjectVal([
                'val' => new \Graphpinator\Parser\Value\Literal('123'),
            ]),
        false,
                ],
            [new \Graphpinator\Parser\Value\Literal('var1'), new \Graphpinator\Parser\Value\VariableRef('var1'), false],
            [
            new \Graphpinator\Parser\Value\Literal(true),
            new \Graphpinator\Parser\Value\ListVal([
                new \Graphpinator\Parser\Value\Literal(true),
            ]),
        false,
                ],
            [
            new \Graphpinator\Parser\Value\Literal(true),
            new \Graphpinator\Parser\Value\ObjectVal([
                'val' => new \Graphpinator\Parser\Value\Literal(true),
            ]),
        false,
                ],
            [new \Graphpinator\Parser\Value\ListVal([]), new \Graphpinator\Parser\Value\ObjectVal([]), false],
            [
            new \Graphpinator\Parser\Value\ListVal([
                new \Graphpinator\Parser\Value\Literal('123'),
            ]),
        new \Graphpinator\Parser\Value\ObjectVal([
                'val1' => new \Graphpinator\Parser\Value\Literal('123'),
            ]),
        false,
                ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Graphpinator\Parser\Value\Value $lhs
     * @param \Graphpinator\Parser\Value\Value $rhs
     * @param bool $result
     */
    public function testSimple(\Graphpinator\Parser\Value\Value $lhs, \Graphpinator\Parser\Value\Value $rhs, bool $result) : void
    {
        self::assertSame($result, $lhs->isSame($rhs));
        self::assertSame($result, $rhs->isSame($lhs));
        self::assertTrue($lhs->isSame($lhs));
        self::assertTrue($rhs->isSame($rhs));
    }
}
