<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class GpsTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [(object) ['lat' => 0.0, 'lng' => 0.0]],
            [(object) ['lat' => -90.0, 'lng' => -180.0]],
            [(object) ['lat' => 90.0, 'lng' => 180.0]],
            [(object) ['lat' => 45.45, 'lng' => 90.90]],
            [(object) ['lat' => -45.45, 'lng' => -90.90]],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [(object) ['lat' => 0, 'lng' => 0.0]],
            [(object) ['lat' => 0.0, 'lng' => 0]],
            [(object) ['lat' => 0.0, 'lng' => null]],
            [(object) ['lat' => null, 'lng' => 0.0]],
            [(object) ['lat' => 0.0, 'lng' => 'string']],
            [(object) ['lat' => 'string', 'y' => 0.0]],
            [(object) ['lng' => 90.0]],
            [(object) ['lat' => 45.0]],
            [true],
            [420],
            [420.42],
            ['beetlejuice'],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \stdClass $rawValue
     * @doesNotPerformAssertions
     */
    public function testValidateValue(\stdClass $rawValue) : void
    {
        $gps = new \Graphpinator\Type\Addon\GpsType();
        $gps->validateResolvedValue($rawValue);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array|\stdClass $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $gps = new \Graphpinator\Type\Addon\GpsType();
        $gps->validateResolvedValue($rawValue);
    }

    public function testInputConstraintDefaultValue() : void
    {
        $gps = new \Graphpinator\Type\Addon\GpsInput();
        $args = $gps->getArguments()->toArray();

        self::assertSame(' @floatConstraint(min: -90, max: 90)', $args['lat']->printConstraints());
        self::assertSame(' @floatConstraint(min: -180, max: 180)', $args['lng']->printConstraints());
    }
}
