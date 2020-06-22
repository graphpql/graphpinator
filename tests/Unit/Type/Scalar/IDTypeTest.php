<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Scalar;

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
     * @param int|string|null $rawValue
     */
    public function testValidateValue($rawValue) : void
    {
        $id = new \Graphpinator\Type\Scalar\IdType();
        $id->validateValue($rawValue);

        self::assertSame($id->getName(), 'ID');
    }

    /**
     * @dataProvider invalidDataProvider
     * @param float|bool|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $id = new \Graphpinator\Type\Scalar\IdType();
        $id->validateValue($rawValue);
    }
}
