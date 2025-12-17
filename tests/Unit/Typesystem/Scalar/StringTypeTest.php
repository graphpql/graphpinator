<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem\Scalar;

use Graphpinator\Common\Path;
use Graphpinator\Typesystem\Spec\StringType;
use Graphpinator\Value\Exception\InvalidValue;
use Graphpinator\Value\Visitor\ConvertRawValueVisitor;
use PHPUnit\Framework\TestCase;

final class StringTypeTest extends TestCase
{
    public static function simpleDataProvider() : array
    {
        return [
            ['abc'],
            ['123'],
            [null],
        ];
    }

    public static function invalidDataProvider() : array
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
        $string = new StringType();
        $value = $string->accept(new ConvertRawValueVisitor($rawValue, new Path()));

        self::assertSame($string, $value->getType());
        self::assertSame($rawValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|float|bool|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(InvalidValue::class);

        $string = new StringType();
        $string->accept(new ConvertRawValueVisitor($rawValue, new Path()));
    }
}
