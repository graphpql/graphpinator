<?php

declare(strict_types=1);

namespace Tests\Type\Scalar;

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
     */
    public function testValidateValue($rawValue): void
    {
        $bool = new \PGQL\Type\Scalar\BooleanType();
        $bool->validateValue($rawValue);

        self::assertSame($bool->getName(), 'Boolean');
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testValidateValueInvalid($rawValue): void
    {
        $this->expectException(\Exception::class);

        $bool = new \PGQL\Type\Scalar\BooleanType();
        $bool->validateValue($rawValue);
    }
}
