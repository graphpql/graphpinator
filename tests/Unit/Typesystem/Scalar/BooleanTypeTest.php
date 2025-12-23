<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem\Scalar;

use Graphpinator\Common\Path;
use Graphpinator\Typesystem\Spec\BooleanType;
use Graphpinator\Value\Exception\InvalidValue;
use Graphpinator\Value\Visitor\ConvertRawValueVisitor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class BooleanTypeTest extends TestCase
{
    public static function simpleDataProvider() : array
    {
        return [
            [true],
            [false],
            [null],
        ];
    }

    public static function invalidDataProvider() : array
    {
        return [
            [123],
            [123.123],
            ['123'],
            [[]],
        ];
    }

    #[DataProvider('simpleDataProvider')]
    public function testValidateValue($rawValue) : void
    {
        $bool = new BooleanType();
        $value = $bool->accept(new ConvertRawValueVisitor($rawValue, new Path()));

        self::assertSame($bool, $value->getType());
        self::assertSame($rawValue, $value->getRawValue());
    }

    #[DataProvider('invalidDataProvider')]
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(InvalidValue::class);

        $bool = new BooleanType();
        $bool->accept(new ConvertRawValueVisitor($rawValue, new Path()));
    }
}
