<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class DateTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['2010-01-01'],
            ['2010-31-01'],
            ['2010-28-02'],
            ['2010-29-02'],
            ['2010-30-04'],
            ['2010-12-12'],
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
            ['2010-01-01 12:50:5'],
            ['2010-01-01 12:50:'],
            ['2010-01-01 12:50'],
            ['2010-01-01 12:5'],
            ['2010-01-01 12:'],
            ['2010-01-01 12'],
            ['2010-01-01 1'],
            ['420-01-2010'],
            ['01-420-2010'],
            ['2010-01-011'],
            ['2010-01-01 255:50:50'],
            ['2010-01-01 12:705:50'],
            ['2010-01-01 12:50:705'],
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
        $date = new \Graphpinator\Type\Addon\DateType();
        $date->validateResolvedValue($rawValue);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $date = new \Graphpinator\Type\Addon\DateType();
        $date->validateResolvedValue($rawValue);
    }
}
