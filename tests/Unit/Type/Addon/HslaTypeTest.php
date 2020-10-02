<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class HslaTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [(object) ['hue' => 360, 'saturation' => 100, 'lightness' => 100, 'alpha' => 1.0]],
            [(object) ['hue' => 0, 'saturation' => 0, 'lightness' => 0, 'alpha' => 0.0]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 150, 'saturation' => 20, 'lightness' => 80, 'alpha' => 0.8]],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [(object) ['hue' => 420, 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 420, 'lightness' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => 420, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => 42]],
            [(object) ['saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'lightness' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50]],
            [(object) ['hue' => null, 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => null, 'lightness' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => null, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => null]],
            [(object) ['hue' => 180.42, 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 50.42, 'lightness' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50.42, 'alpha' => 0.5]],
            [(object) ['hue' => 'beetlejuice', 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 'beetlejuice', 'lightness' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => 'beetlejuice', 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => 'beetlejuice']],
            [(object) ['hue' => [], 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => [], 'lightness' => 50, 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => [], 'alpha' => 0.5]],
            [(object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => []]],
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
        $hsla = new \Graphpinator\Type\Addon\HslaType();
        $hsla->validateValue($rawValue);
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

    public function testInputDefaultValue() : void
    {
        $hsla = new \Graphpinator\Type\Addon\HslaInput();
        $args = $hsla->getArguments()->toArray();

        self::assertSame(0, $args['hue']->getDefaultValue()->getRawValue());
        self::assertSame(0, $args['saturation']->getDefaultValue()->getRawValue());
        self::assertSame(0, $args['lightness']->getDefaultValue()->getRawValue());
        self::assertSame(0.0, $args['alpha']->getDefaultValue()->getRawValue());
    }

    public function testInputConstraintDefaultValue() : void
    {
        $hsla = new \Graphpinator\Type\Addon\HslaInput();
        $args = $hsla->getArguments()->toArray();

        self::assertSame(' @intConstraint(min: 0, max: 360)', $args['hue']->printConstraints());
        self::assertSame(' @intConstraint(min: 0, max: 100)', $args['saturation']->printConstraints());
        self::assertSame(' @intConstraint(min: 0, max: 100)', $args['lightness']->printConstraints());
        self::assertSame(' @floatConstraint(min: 0, max: 1)', $args['alpha']->printConstraints());
    }
}
