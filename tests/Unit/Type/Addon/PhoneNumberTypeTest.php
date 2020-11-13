<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class PhoneNumberTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['+420123456789'],
            ['+42012345678'],
            ['+42123456789'],
            ['+888123456789'],
            ['+88123456789'],
            ['+8123456789'],
            ['+88812345678'],
            ['+812345678'],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            ['123456789'],
            ['12345678'],
            ['222123456789'],
            ['22123456789'],
            ['2123456789'],
            ['22212345678'],
            ['2212345678'],
            ['212345678'],
            ['+23456789'],
            ['+3456789'],
            ['+456789'],
            ['+56789'],
            ['+6789'],
            ['+789'],
            ['+89'],
            ['+9'],
            ['+'],
            [true],
            [420],
            [420.42],
            ['beetlejuice'],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param string $rawValue
     * @doesNotPerformAssertions
     */
    public function testValidateValue(string $rawValue) : void
    {
        $phoneNumber = new \Graphpinator\Type\Addon\PhoneNumberType();
        $phoneNumber->validateResolvedValue($rawValue);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $phoneNumber = new \Graphpinator\Type\Addon\PhoneNumberType();
        $phoneNumber->validateResolvedValue($rawValue);
    }
}
