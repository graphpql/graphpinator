<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem\Scalar;

use Graphpinator\Common\Path;
use Graphpinator\Exception\Value\InvalidValue;
use Graphpinator\Typesystem\Spec\IntType;
use Graphpinator\Value\ConvertRawValueVisitor;
use PHPUnit\Framework\TestCase;

final class IntTypeTest extends TestCase
{
    public static function simpleDataProvider() : array
    {
        return [
            [123],
            [45],
            [null],
        ];
    }

    public static function invalidDataProvider() : array
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
        $int = new IntType();
        $value = $int->accept(new ConvertRawValueVisitor($rawValue, new Path()));

        self::assertSame($int, $value->getType());
        self::assertSame($rawValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param bool|float|string|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(InvalidValue::class);

        $int = new IntType();
        $int->accept(new ConvertRawValueVisitor($rawValue, new Path()));
    }
}
