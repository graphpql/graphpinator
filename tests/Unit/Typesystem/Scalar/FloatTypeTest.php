<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem\Scalar;

use Graphpinator\Common\Path;
use Graphpinator\Typesystem\Spec\FloatType;
use Graphpinator\Value\Exception\InvalidValue;
use Graphpinator\Value\Visitor\ConvertRawValueVisitor;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('simpleDataProvider')]
    public function testValidateValue($rawValue, $resultValue) : void
    {
        $float = new FloatType();
        $value = $float->accept(new ConvertRawValueVisitor($rawValue, new Path()));

        self::assertSame($float, $value->getType());
        self::assertSame($resultValue, $value->getRawValue());
    }

    #[DataProvider('invalidDataProvider')]
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(InvalidValue::class);

        $float = new FloatType();
        $float->accept(new ConvertRawValueVisitor($rawValue, new Path()));
    }
}
