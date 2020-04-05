<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Parser\Value;

final class NamedValueTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [new \Graphpinator\Parser\Value\Literal(123), 'name'],
            [new \Graphpinator\Parser\Value\Literal(123.123), 'name'],
            [new \Graphpinator\Parser\Value\Literal('123'), 'name'],
            [new \Graphpinator\Parser\Value\Literal(true), 'name'],
            [new \Graphpinator\Parser\Value\ListVal([]), 'name'],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testSimple(\Graphpinator\Parser\Value\Value $value, string $name): void
    {
        $obj = new \Graphpinator\Parser\Value\NamedValue($value, $name);

        self::assertSame($name, $obj->getName());
        self::assertSame($value, $obj->getValue());
    }
}
