<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class PostalCodeTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['123 45'],
            ['000 00'],
            ['999 99'],
            ['351 00'],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            ['123 4'],
            ['123'],
            ['123'],
            ['12'],
            ['1'],
            ['23 45'],
            ['3 45'],
            ['23 456'],
            ['3 456'],
            ['23456'],
            ['2345 6'],
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
        $postalCode = new \Graphpinator\Type\Addon\PostalCodeType();
        $postalCode->validateResolvedValue($rawValue);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $postalCode = new \Graphpinator\Type\Addon\PostalCodeType();
        $postalCode->validateResolvedValue($rawValue);
    }
}
