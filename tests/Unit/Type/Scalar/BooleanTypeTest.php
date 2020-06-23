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
        $bool->validateValue($rawValue);

        self::assertSame($bool->getName(), 'Boolean');
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|float|string|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $bool = new \Graphpinator\Type\Scalar\BooleanType();
        $bool->validateValue($rawValue);
    }
}
