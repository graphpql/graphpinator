<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class VoidTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [null],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [true],
            [420],
            [420.42],
            ['beetlejuice'],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param void $rawValue
     * @doesNotPerformAssertions
     */
    public function testValidateValue($rawValue) : void
    {
        $void = new \Graphpinator\Type\Addon\VoidType();
        $void->validateResolvedValue($rawValue);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $void = new \Graphpinator\Type\Addon\VoidType();
        $void->validateResolvedValue($rawValue);
    }
}
