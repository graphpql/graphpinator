<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class RgbaTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [(object) ['red' => 255, 'green' => 255, 'blue' => 255, 'alpha' => 1.0]],
            [(object) ['red' => 0, 'green' => 0, 'blue' => 0, 'alpha' => 0.0]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 150, 'green' => 20, 'blue' => 80, 'alpha' => 0.8]],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [(object) ['red' => 420, 'green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 420, 'blue' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => 420, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => 50, 'alpha' => 42]],
            [(object) ['green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'blue' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => 50]],
            [(object) ['red' => null, 'green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => null, 'blue' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => null, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => 50, 'alpha' => null]],
            [(object) ['red' => 180.42, 'green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 50.42, 'blue' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => 50.42, 'alpha' => 0.5]],
            [(object) ['red' => 'beetlejuice', 'green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 'beetlejuice', 'blue' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => 'beetlejuice', 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => 50, 'alpha' => 'beetlejuice']],
            [(object) ['red' => [], 'green' => 50, 'blue' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => [], 'blue' => 50, 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => [], 'alpha' => 0.5]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => 50, 'alpha' => []]],
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
        $rgba = new \Graphpinator\Type\Addon\RgbaType();
        $rgba->validateValue($rawValue);
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

    public function testInputDefaultValue() : void
    {
        $rgba = new \Graphpinator\Type\Addon\RgbaInput();
        $args = $rgba->getArguments()->toArray();

        self::assertSame(0, $args['red']->getDefaultValue()->getRawValue());
        self::assertSame(0,$args['green']->getDefaultValue()->getRawValue());
        self::assertSame(0, $args['blue']->getDefaultValue()->getRawValue());
        self::assertSame(0.0, $args['alpha']->getDefaultValue()->getRawValue());
    }

    public function testInputConstraintDefaultValue() : void
    {
        $rgba = new \Graphpinator\Type\Addon\RgbaInput();
        $args = $rgba->getArguments()->toArray();

        self::assertSame(' @intConstraint(min: 0, max: 255)', $args['red']->printConstraints());
        self::assertSame(' @intConstraint(min: 0, max: 255)', $args['green']->printConstraints());
        self::assertSame(' @intConstraint(min: 0, max: 255)', $args['blue']->printConstraints());
        self::assertSame(' @floatConstraint(min: 0, max: 1)', $args['alpha']->printConstraints());
    }
}
