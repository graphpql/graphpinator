<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Scalar;

final class BooleanTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [true],
            [false],
            [null],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [123],
            [123.123],
            ['123'],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param bool|null $rawValue
     */
    public function testValidateValue($rawValue) : void
    {
        $bool = new \Graphpinator\Type\Scalar\BooleanType();
        $value = $bool->createInputedValue($rawValue);

        self::assertSame($bool, $value->getType());
        self::assertSame($rawValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|float|string|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $bool = new \Graphpinator\Type\Scalar\BooleanType();
        $bool->createInputedValue($rawValue);
    }
}
