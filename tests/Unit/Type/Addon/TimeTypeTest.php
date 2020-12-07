<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class TimeTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['00:00:00'],
            ['23:59:59'],
            ['24:00:00'],
            ['12:10:55'],
            ['12:12:12'],
            ['00:00:55'],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            ['120:10:55'],
            ['12:100:55'],
            ['12:10:550'],
            ['1210:55'],
            ['12:1055'],
            [':00:00'],
            ['00:00'],
            ['0:00'],
            [':00'],
            ['00'],
            ['0'],
            [true],
            [420],
            [420.42],
            ['beetlejuice'],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param string $rawValue
     */
    public function testValidateValue(string $rawValue) : void
    {
        $dateTime = new \Graphpinator\Type\Addon\TimeType();
        $value = $dateTime->createInputedValue($rawValue);

        self::assertSame($dateTime, $value->getType());
        self::assertSame($rawValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $dateTime = new \Graphpinator\Type\Addon\TimeType();
        $dateTime->createInputedValue($rawValue);
    }
}
