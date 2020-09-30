<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class HslTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [['hue' => 360, 'saturation' => 100, 'lightness' => 100]],
            [['hue' => 0, 'saturation' => 0, 'lightness' => 0]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => 50]],
            [['hue' => 150, 'saturation' => 20, 'lightness' => 80]],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [['hue' => 420, 'saturation' => 20, 'lightness' => 80]],
            [['hue' => 150, 'saturation' => 420, 'lightness' => 80]],
            [['hue' => 150, 'saturation' => 20, 'lightness' => 420]],
            [['saturation' => 20, 'lightness' => 80]],
            [['hue' => 150, 'lightness' => 80]],
            [['hue' => 150, 'saturation' => 20]],
            [['hue' => null, 'saturation' => 20, 'lightness' => 80]],
            [['hue' => 150, 'saturation' => null, 'lightness' => 80]],
            [['hue' => 150, 'saturation' => 20, 'lightness' => null]],
            [['hue' => 150.42, 'saturation' => 20, 'lightness' => 80]],
            [['hue' => 150, 'saturation' => 20.42, 'lightness' => 80]],
            [['hue' => 150, 'saturation' => 20, 'lightness' => 80.42]],
            [['hue' => 'beetlejuice', 'saturation' => 50, 'lightness' => 50]],
            [['hue' => 180, 'saturation' => 'beetlejuice', 'lightness' => 50]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => 'beetlejuice']],
            [['hue' => [], 'saturation' => 50, 'lightness' => 50]],
            [['hue' => 180, 'saturation' => [], 'lightness' => 50]],
            [['hue' => 180, 'saturation' => 50, 'lightness' => []]],
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
     * @doesNotPerformAssertions
     */
    public function testValidateValue($rawValue) : void
    {
        $hsl = new \Graphpinator\Type\Addon\HslType();
        $hsl->validateValue($rawValue);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $hsl = new \Graphpinator\Type\Addon\HslType();
        $hsl->validateValue($rawValue);
    }
}
