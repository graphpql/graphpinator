<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class RgbTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [(object) ['red' => 255, 'green' => 255, 'blue' => 255]],
            [(object) ['red' => 0, 'green' => 0, 'blue' => 0]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => 50]],
            [(object) ['red' => 150, 'green' => 20, 'blue' => 80]],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [(object) ['red' => 420, 'green' => 20, 'blue' => 80]],
            [(object) ['red' => 150, 'green' => 420, 'blue' => 80]],
            [(object) ['red' => 150, 'green' => 20, 'blue' => 420]],
            [(object) ['green' => 20, 'blue' => 80]],
            [(object) ['red' => 150, 'blue' => 80]],
            [(object) ['red' => 150, 'green' => 20]],
            [(object) ['red' => null, 'green' => 20, 'blue' => 80]],
            [(object) ['red' => 150, 'green' => null, 'blue' => 80]],
            [(object) ['red' => 150, 'green' => 20, 'blue' => null]],
            [(object) ['red' => 150.42, 'green' => 20, 'blue' => 80]],
            [(object) ['red' => 150, 'green' => 20.42, 'blue' => 80]],
            [(object) ['red' => 150, 'green' => 20, 'blue' => 80.42]],
            [(object) ['red' => 'beetlejuice', 'green' => 50, 'blue' => 50]],
            [(object) ['red' => 180, 'green' => 'beetlejuice', 'blue' => 50]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => 'beetlejuice']],
            [(object) ['red' => [], 'green' => 50, 'blue' => 50]],
            [(object) ['red' => 180, 'green' => [], 'blue' => 50]],
            [(object) ['red' => 180, 'green' => 50, 'blue' => []]],
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
        $rgb = new \Graphpinator\Type\Addon\RgbType();
        $rgb->validateValue($rawValue);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $rgb = new \Graphpinator\Type\Addon\RgbType();
        $rgb->validateValue($rawValue);
    }

    public function testInputDefaultValue() : void
    {
        $rgb = new \Graphpinator\Type\Addon\RgbInput();
        $args = $rgb->getArguments()->toArray();

        self::assertSame(0, $args['red']->getDefaultValue()->getRawValue());
        self::assertSame(0, $args['green']->getDefaultValue()->getRawValue());
        self::assertSame(0, $args['blue']->getDefaultValue()->getRawValue());
    }

    public function testInputConstraintDefaultValue() : void
    {
        $rgb = new \Graphpinator\Type\Addon\RgbInput();
        $args = $rgb->getArguments()->toArray();

        self::assertSame(' @intConstraint(min: 0, max: 255)', $args['red']->printConstraints());
        self::assertSame(' @intConstraint(min: 0, max: 255)', $args['green']->printConstraints());
        self::assertSame(' @intConstraint(min: 0, max: 255)', $args['blue']->printConstraints());
    }
}
