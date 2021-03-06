<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Scalar;

final class IDTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [123, '123'],
            ['123', '123'],
            [null, null],
        ];
    }

    public function invalidDataProvider() : array
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
        $id = new \Graphpinator\Type\Spec\IdType();
        $value = $id->createInputedValue($rawValue);

        self::assertSame($id, $value->getType());
        self::assertSame($resultValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param float|bool|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $id = new \Graphpinator\Type\Spec\IdType();
        $id->createInputedValue($rawValue);
    }
}
