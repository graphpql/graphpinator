<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class RgbaTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [['red' => 255, 'green' => 255, 'blue' => 255, 'alpha' => 1]],
            [['red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 0]],
            [['red' => 180, 'green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [['red' => 150, 'green' => 20, 'blue' => 80, 'alpha' => 0.8]],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [['red' => 420, 'green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [['red' => 180, 'green' => 420, 'blue' => 50, 'alpha' => 0.5]],
            [['red' => 180, 'green' => 50, 'blue' => 420, 'alpha' => 0.5]],
            [['red' => 180, 'green' => 50, 'blue' => 50, 'alpha' => 42]],
            [['green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [['red' => 180, 'blue' => 50, 'alpha' => 0.5]],
            [['red' => 180, 'green' => 50, 'alpha' => 0.5]],
            [['red' => 180, 'green' => 50, 'blue' => 50]],
            [['red' => null, 'green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [['red' => 180, 'green' => null, 'blue' => 50, 'alpha' => 0.5]],
            [['red' => 180, 'green' => 50, 'blue' => null, 'alpha' => 0.5]],
            [['red' => 180, 'green' => 50, 'blue' => 50, 'alpha' => null]],
            [['red' => 180.42, 'green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [['red' => 180, 'green' => 50.42, 'blue' => 50, 'alpha' => 0.5]],
            [['red' => 180, 'green' => 50, 'blue' => 50.42, 'alpha' => 0.5]],
            [['red' => 'beetlejuice', 'green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [['red' => 180, 'green' => 'beetlejuice', 'blue' => 50, 'alpha' => 0.5]],
            [['red' => 180, 'green' => 50, 'blue' => 'beetlejuice', 'alpha' => 0.5]],
            [['red' => 180, 'green' => 50, 'blue' => 50, 'alpha' => 'beetlejuice']],
            [['red' => [], 'green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [['red' => 180, 'green' => [], 'blue' => 50, 'alpha' => 0.5]],
            [['red' => 180, 'green' => 50, 'blue' => [], 'alpha' => 0.5]],
            [['red' => 180, 'green' => 50, 'blue' => 50, 'alpha' => []]],
            [true],
            [420],
            [420.42],
            ['beetlejuice'],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param array $rawValue
     */
    public function testValidateValue($rawValue) : void
    {
        $rgba = new \Graphpinator\Type\Addon\RgbaType();
        $rgba->validateValue($rawValue);

        self::assertSame($rgba->getName(), 'rgba');
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $rgba = new \Graphpinator\Type\Addon\RgbaType();
        $rgba->validateValue($rawValue);
    }
}
