<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class DateTimeTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['01-01-2010 00:00:00'],
            ['31-01-2010 23:59:59'],
            ['28-02-2010 24:00:00'],
            ['29-02-2016 12:10:55'],
            ['30-04-2010 12:12:12'],
            ['12-12-2015 00:00:55'],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            ['404-01-2010 12:50:50'],
            ['01-404-2010 12:50:50'],
            ['01-01-42042 12:50:50'],
            ['01-01- 12:50:50'],
            ['01-01 12:50:50'],
            ['01-0 12:50:50'],
            ['01- 12:50:50'],
            ['0 12:50:50'],
            ['12:50:50'],
            ['01-01-2010 12:50:5'],
            ['01-01-2010 12:50:'],
            ['01-01-2010 12:50'],
            ['01-01-2010 12:5'],
            ['01-01-2010 12:'],
            ['01-01-2010 12'],
            ['01-01-2010 1'],
            ['01-01-2010'],
            ['01-01-2010 255:50:50'],
            ['01-01-2010 12:705:50'],
            ['01-01-2010 12:50:705'],
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
        $dateTime = new \Graphpinator\Type\Addon\DateTimeType();
        $dateTime->validateValue($rawValue);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $dateTime = new \Graphpinator\Type\Addon\DateTimeType();
        $dateTime->validateValue($rawValue);
    }
}
