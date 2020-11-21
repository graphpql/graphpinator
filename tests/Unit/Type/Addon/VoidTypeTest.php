<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class VoidTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [null],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [true],
            [420],
            [420.42],
            ['beetlejuice'],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param void $rawValue
     */
    public function testValidateValue($rawValue) : void
    {
        $void = new \Graphpinator\Type\Addon\VoidType();
        $value = $void->createInputedValue($rawValue);

        self::assertSame($void, $value->getType());
        self::assertSame($rawValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $void = new \Graphpinator\Type\Addon\VoidType();
        $void->createInputedValue($rawValue);
    }
}
