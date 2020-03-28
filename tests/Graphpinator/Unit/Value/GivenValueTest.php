<?php

declare(strict_types=1);

namespace Infinityloop\Tests\Graphpinator\Unit\Value;

final class GivenValueTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [123, 'name'],
            [123.123, 'name'],
            ['123', 'name'],
            [true, 'name'],
            [[], 'name'],
            [[123, true], 'name'],
            [new \Infinityloop\Graphpinator\Value\GivenValue('inner', 'a'), 'name'],
            [[new \Infinityloop\Graphpinator\Value\GivenValue('inner', 'a')], 'name'],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testSimple($rawValue, string $name): void
    {
        $value = new \Infinityloop\Graphpinator\Value\GivenValue($rawValue, $name);

        self::assertSame($name, $value->getName());
        self::assertSame($rawValue, $value->getValue());
    }
}
