<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Scalar;

final class IntTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [123],
            [45],
            [null],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [true],
            [123.123],
            ['123'],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param int|null $rawValue
     */
    public function testValidateValue($rawValue) : void
    {
        $int = new \Graphpinator\Type\Scalar\IntType();
        $value = $int->createInputedValue($rawValue);

        self::assertSame($int, $value->getType());
        self::assertSame($rawValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param bool|float|string|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $int = new \Graphpinator\Type\Scalar\IntType();
        $int->createInputedValue($rawValue);
    }
}
