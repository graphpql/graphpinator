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
     */
    public function testValidateValue($rawValue) : void
    {
        $rgb = \Graphpinator\Tests\Spec\TestSchema::getType('Rgb');
        $value = $rgb->createResolvedValue($rawValue);

        self::assertSame($rgb, $value->getType());
        self::assertSame($rawValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $rgb = \Graphpinator\Tests\Spec\TestSchema::getType('Rgb');
        $rgb->createResolvedValue($rawValue);
    }
}
