<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class IPv4TypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['255.255.255.255'],
            ['0.0.0.0'],
            ['128.0.1.1'],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            ['420.255.255.255'],
            ['255.420.255.255'],
            ['255.255.420.255'],
            ['255.255.255.420'],
            ['255.FF.255.255'],
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
        $ipv4 = new \Graphpinator\Type\Addon\IPv4Type();
        $ipv4->validateValue($rawValue);

        self::assertSame($ipv4->getName(), 'IPv4');
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $ipv4 = new \Graphpinator\Type\Addon\IPv4Type();
        $ipv4->validateValue($rawValue);
    }
}
