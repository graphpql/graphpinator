<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class HslTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [(object) ['hue' => 360, 'saturation' => 100, 'lightness' => 100]],
            [(object) ['hue' => 0, 'saturation' => 0, 'lightness' => 0]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50]],
            [(object) ['hue' => 150, 'saturation' => 20, 'lightness' => 80]],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [(object) ['hue' => 150, 'lightness' => 80]],
            [(object) ['hue' => 150, 'saturation' => 20]],
            [(object) ['hue' => null, 'saturation' => 20, 'lightness' => 80]],
            [(object) ['hue' => 150, 'saturation' => null, 'lightness' => 80]],
            [(object) ['hue' => 150, 'saturation' => 20, 'lightness' => null]],
            [(object) ['hue' => 150.42, 'saturation' => 20, 'lightness' => 80]],
            [(object) ['hue' => 150, 'saturation' => 20.42, 'lightness' => 80]],
            [(object) ['hue' => 150, 'saturation' => 20, 'lightness' => 80.42]],
            [(object) ['hue' => 'beetlejuice', 'saturation' => 50, 'lightness' => 50]],
            [(object) ['hue' => 180, 'saturation' => 'beetlejuice', 'lightness' => 50]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => 'beetlejuice']],
            [(object) ['hue' => [], 'saturation' => 50, 'lightness' => 50]],
            [(object) ['hue' => 180, 'saturation' => [], 'lightness' => 50]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => []]],
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
        $hsl->validateResolvedValue($rawValue);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $hsl = new \Graphpinator\Type\Addon\HslType();
        $hsl->validateResolvedValue($rawValue);
    }

    public function testInputConstraintDefaultValue() : void
    {
        $hsl = new \Graphpinator\Type\Addon\HslInput();
        $args = $hsl->getArguments()->toArray();

        self::assertSame(' @intConstraint(min: 0, max: 360)', $args['hue']->printConstraints());
        self::assertSame(' @intConstraint(min: 0, max: 100)', $args['saturation']->printConstraints());
        self::assertSame(' @intConstraint(min: 0, max: 100)', $args['lightness']->printConstraints());
    }
}
