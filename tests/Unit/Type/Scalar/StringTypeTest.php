<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Scalar;

final class StringTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['abc'],
            ['123'],
            [null],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [123],
            [123.123],
            [true],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param string|null $rawValue
     */
    public function testValidateValue($rawValue) : void
    {
        $string = new \Graphpinator\Type\Spec\StringType();
        $value = $string->createInputedValue($rawValue);

        self::assertSame($string, $value->getType());
        self::assertSame($rawValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|float|bool|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $string = new \Graphpinator\Type\Spec\StringType();
        $string->createInputedValue($rawValue);
    }
}
