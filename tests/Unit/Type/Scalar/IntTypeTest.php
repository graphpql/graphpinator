<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Scalar;

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
     * @param int|null $rawValue
     */
    public function testValidateValue($rawValue) : void
    {
        $int = new \Graphpinator\Type\Scalar\IntType();
        $int->validateResolvedValue($rawValue);

        self::assertSame($int->getName(), 'Int');
    }

    /**
     * @dataProvider invalidDataProvider
     * @param bool|float|string|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $int = new \Graphpinator\Type\Scalar\IntType();
        $int->validateResolvedValue($rawValue);
    }
}
