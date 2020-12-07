<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class EmailAddressTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['test@test.com'],
            ['test@test.cz'],
            ['test@test.eu'],
            ['test@test.sk'],
            ['test@test.org'],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            ['testtest.org'],
            ['test@testcom'],
            ['@test.com'],
            ['test.com'],
            ['@'],
            ['test@.com'],
            ['test@test'],
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
     */
    public function testValidateValue(string $rawValue) : void
    {
        $email = new \Graphpinator\Type\Addon\EmailAddressType();
        $value = $email->createInputedValue($rawValue);

        self::assertSame($email, $value->getType());
        self::assertSame($rawValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $date = new \Graphpinator\Type\Addon\EmailAddressType();
        $date->createInputedValue($rawValue);
    }
}
