<?php

declare(strict_types=1);

namespace Tests\Value;

final class ValueTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [123],
            [123.123],
            ['123'],
            [true],
            [[]],
            [[123, true]],
            [new \PGQL\Value\Value('inner')],
            [[new \PGQL\Value\Value('inner')]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testSimple($rawValue): void
    {
        $value = new \PGQL\Value\Value($rawValue);

        self::assertSame($rawValue, $value->getValue());
        self::assertSame($rawValue, $value->jsonSerialize());
    }
}
