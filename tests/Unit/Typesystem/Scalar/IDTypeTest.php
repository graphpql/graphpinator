<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem\Scalar;

use Graphpinator\Common\Path;
use Graphpinator\Exception\Value\InvalidValue;
use Graphpinator\Typesystem\Spec\IdType;
use Graphpinator\Value\ConvertRawValueVisitor;
use PHPUnit\Framework\TestCase;

final class IDTypeTest extends TestCase
{
    public static function simpleDataProvider() : array
    {
        return [
            [123, '123'],
            ['123', '123'],
            [null, null],
        ];
    }

    public static function invalidDataProvider() : array
    {
        return [
            [123.123],
            [true],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param int|string|null $rawValue
     * @param ?string $resultValue
     */
    public function testValidateValue(string|int|null $rawValue, ?string $resultValue) : void
    {
        $id = new IdType();
        $value = $id->accept(new ConvertRawValueVisitor($rawValue, new Path()));

        self::assertSame($id, $value->getType());
        self::assertSame($resultValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param float|bool|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(InvalidValue::class);

        $id = new IdType();
        $id->accept(new ConvertRawValueVisitor($rawValue, new Path()));
    }
}
