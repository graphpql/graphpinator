<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Scalar;

final class StringTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['abc'],
            ['123'],
            [null],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [123],
            [123.123],
            [true],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param string|null $rawValue
     */
    public function testValidateValue($rawValue) : void
    {
        $string = new \Graphpinator\Type\Scalar\StringType();
        $string->validateResolvedValue($rawValue);

        self::assertSame($string->getName(), 'String');
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|float|bool|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $string = new \Graphpinator\Type\Scalar\StringType();
        $string->validateResolvedValue($rawValue);
    }
}
