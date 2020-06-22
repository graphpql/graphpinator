<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Scalar;

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
     * @param float|null $rawValue
     */
    public function testValidateValue($rawValue) : void
    {
        $float = new \Graphpinator\Type\Scalar\FloatType();
        $float->validateValue($rawValue);

        self::assertSame($float->getName(), 'Float');
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $float = new \Graphpinator\Type\Scalar\FloatType();
        $float->validateValue($rawValue);
    }
}
