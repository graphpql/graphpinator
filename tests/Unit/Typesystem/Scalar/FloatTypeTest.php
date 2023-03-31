<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem\Scalar;

final class FloatTypeTest extends \PHPUnit\Framework\TestCase
{
    public static function simpleDataProvider() : array
    {
        return [
            [123.123, 123.123],
            [456.789, 456.789],
            [0.1, 0.1],
            [123, 123.0],
            [null, null],
        ];
    }

    public static function invalidDataProvider() : array
    {
        return [
            [true],
            ['123'],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param float|null $rawValue
     * @param float|null $resultValue
     */
    public function testValidateValue($rawValue, $resultValue) : void
    {
        $float = new \Graphpinator\Typesystem\Spec\FloatType();
        $value = $float->accept(new \Graphpinator\Value\ConvertRawValueVisitor($rawValue, new \Graphpinator\Common\Path()));

        self::assertSame($float, $value->getType());
        self::assertSame($resultValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $float = new \Graphpinator\Typesystem\Spec\FloatType();
        $float->accept(new \Graphpinator\Value\ConvertRawValueVisitor($rawValue, new \Graphpinator\Common\Path()));
    }
}
