<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem\Scalar;

use Graphpinator\Common\Path;
use Graphpinator\Typesystem\Spec\IntType;
use Graphpinator\Value\Exception\InvalidValue;
use Graphpinator\Value\Visitor\ConvertRawValueVisitor;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('simpleDataProvider')]
    public function testValidateValue($rawValue) : void
    {
        $int = new IntType();
        $value = $int->accept(new ConvertRawValueVisitor($rawValue, new Path()));

        self::assertSame($int, $value->getType());
        self::assertSame($rawValue, $value->getRawValue());
    }

    #[DataProvider('invalidDataProvider')]
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(InvalidValue::class);

        $int = new IntType();
        $int->accept(new ConvertRawValueVisitor($rawValue, new Path()));
    }
}
