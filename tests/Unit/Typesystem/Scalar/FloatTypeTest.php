<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem\Scalar;

use Graphpinator\Common\Path;
use Graphpinator\Exception\Value\InvalidValue;
use Graphpinator\Typesystem\Spec\FloatType;
use Graphpinator\Value\ConvertRawValueVisitor;
use PHPUnit\Framework\TestCase;

final class FloatTypeTest extends TestCase
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
        $float = new FloatType();
        $value = $float->accept(new ConvertRawValueVisitor($rawValue, new Path()));

        self::assertSame($float, $value->getType());
        self::assertSame($resultValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(InvalidValue::class);

        $float = new FloatType();
        $float->accept(new ConvertRawValueVisitor($rawValue, new Path()));
    }
}
