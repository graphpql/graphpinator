<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class PointTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [(object) ['x' => 0.0, 'y' => 0.0]],
            [(object) ['x' => 450.0, 'y' => 450.0]],
            [(object) ['x' => -450.0, 'y' => -450.0]],
            [(object) ['x' => 420.42, 'y' => -420.42]],
            [(object) ['x' => -420.42, 'y' => 420.42]],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [(object) ['x' => 0, 'y' => 0.0]],
            [(object) ['x' => 0.0, 'y' => 0]],
            [(object) ['x' => 0.0]],
            [(object) ['y' => 0.0]],
            [(object) ['x' => 0.0, 'y' => null]],
            [(object) ['x' => null, 'y' => 0.0]],
            [(object) ['x' => 0.0, 'y' => 'string']],
            [(object) ['x' => 'string', 'y' => 0.0]],
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
        $point = new \Graphpinator\Type\Addon\PointType();
        $point->validateResolvedValue($rawValue);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array|\stdClass $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $point = new \Graphpinator\Type\Addon\PointType();
        $point->validateResolvedValue($rawValue);
    }

    public function testInputDefaultValue() : void
    {
        $point = new \Graphpinator\Type\Addon\PointInput();
        $args = $point->getArguments()->toArray();

        self::assertSame(0.0, $args['x']->getDefaultValue()->getRawValue());
        self::assertSame(0.0, $args['y']->getDefaultValue()->getRawValue());
    }
}
