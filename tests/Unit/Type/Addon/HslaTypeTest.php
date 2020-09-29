<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class HslaTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [['hue' => 360, 'saturation' => 100, 'lightness' => 100, 'alpha' => 1]],
            [['hue' => 0, 'saturation' => 0, 'lightness' => 0, 'alpha' => 0]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [['hue' => 150, 'saturation' => 20, 'lightness' => 80, 'alpha' => 0.8]],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [['hue' => 420, 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 420, 'lightness' => 50, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => 420, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => 42]],
            [['saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [['hue' => 180, 'lightness' => 50, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 50, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => 50]],
            [['hue' => null, 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => null, 'lightness' => 50, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => null, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => null]],
            [['hue' => 180.42, 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 50.42, 'lightness' => 50, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => 50.42, 'alpha' => 0.5]],
            [['hue' => 'beetlejuice', 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 'beetlejuice', 'lightness' => 50, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => 'beetlejuice', 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => 'beetlejuice']],
            [['hue' => [], 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => [], 'lightness' => 50, 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => [], 'alpha' => 0.5]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => []]],
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
        $hsla = new \Graphpinator\Type\Addon\HslaType();
        $hsla->validateValue($rawValue);

        self::assertSame($hsla->getName(), 'HSLA');
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $hsla = new \Graphpinator\Type\Addon\HslaType();
        $hsla->validateValue($rawValue);
    }
}
