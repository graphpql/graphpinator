<?php

declare(strict_types=1);

namespace Tests\Type\Scalar;

final class IntTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [123],
            [45],
            [null],
        ];
    }

    public function invalidDataProvider() : array
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
     */
    public function testValidateValue($rawValue): void
    {
        $int = new \PGQL\Type\Scalar\IntType();
        $int->validateValue($rawValue);

        self::assertSame($int->getName(), 'Int');
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testValidateValueInvalid($rawValue): void
    {
        $this->expectException(\Exception::class);

        $int = new \PGQL\Type\Scalar\IntType();
        $int->validateValue($rawValue);
    }
}
