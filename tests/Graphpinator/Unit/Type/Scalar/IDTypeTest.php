<?php

declare(strict_types=1);

namespace Tests\Type\Scalar;

final class IDTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [123],
            ['123'],
            [null],
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
     */
    public function testValidateValue($rawValue): void
    {
        $id = new \PGQL\Type\Scalar\IdType();
        $id->validateValue($rawValue);

        self::assertSame($id->getName(), 'ID');
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testValidateValueInvalid($rawValue): void
    {
        $this->expectException(\Exception::class);

        $id = new \PGQL\Type\Scalar\IdType();
        $id->validateValue($rawValue);
    }
}
