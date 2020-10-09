<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class MacTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['aa:aa:aa:aa:aa:aa'],
            ['ff:ff:ff:ff:ff:ff'],
            ['00:00:00:00:00:00'],
            ['99:99:99:99:99:99'],
            ['AA:AA:AA:AA:AA:AA'],
            ['FF:FF:FF:FF:FF:FF'],
            ['0A-23-11-F0-AA-D0'],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            ['0A23-11-F0-AA-D0'],
            ['0A-2311-F0-AA-D0'],
            ['0A-23-11F0-AA-D0'],
            ['0A-23-11-F0AA-D0'],
            ['0A-23-11-F0-AAD0'],
            ['0AA-23-11-F0-AA-D0'],
            ['0A-23A-11-F0-AA-D0'],
            ['0A-23-11A-F0-AA-D0'],
            ['0A-23-11-F0A-AA-D0'],
            ['0A-23-11-F0-AAA-D0A'],
            ['0-23-11-F0-AA-D0'],
            ['0A-2-11-F0-AA-D0'],
            ['0A-23-1-F0-AA-D0'],
            ['0A-23-11-F-AA-D0'],
            ['0A-23-11-F0-A-D0'],
            ['0A-23-11-F0-AA-D'],
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
        $mac = new \Graphpinator\Type\Addon\MacType();
        $mac->validateResolvedValue($rawValue);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $mac = new \Graphpinator\Type\Addon\MacType();
        $mac->validateResolvedValue($rawValue);
    }
}
