<?php

declare(strict_types=1);

namespace Infinityloop\Tests\Graphpinator\Unit\Type\Scalar;

final class FloatTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [123.123],
            [456.789],
            [0.1],
            [null],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [123],
            [true],
            ['123'],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testValidateValue($rawValue): void
    {
        $float = new \Infinityloop\Graphpinator\Type\Scalar\FloatType();
        $float->validateValue($rawValue);

        self::assertSame($float->getName(), 'Float');
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testValidateValueInvalid($rawValue): void
    {
        $this->expectException(\Exception::class);

        $float = new \Infinityloop\Graphpinator\Type\Scalar\FloatType();
        $float->validateValue($rawValue);
    }
}
