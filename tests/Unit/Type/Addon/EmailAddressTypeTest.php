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
     * @doesNotPerformAssertions
     */
    public function testValidateValue(string $rawValue) : void
    {
        $date = new \Graphpinator\Type\Addon\EmailAddressType();
        $date->validateValue($rawValue);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $date = new \Graphpinator\Type\Addon\EmailAddressType();
        $date->validateValue($rawValue);
    }
}
