<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class RgbTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [['red' => 255, 'green' => 255, 'blue' => 255]],
            [['red' => 0, 'green' => 0, 'blue' => 0]],
            [['red' => 180, 'green' => 50, 'blue' => 50]],
            [['red' => 150, 'green' => 20, 'blue' => 80]],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [['red' => 420, 'green' => 20, 'blue' => 80]],
            [['red' => 150, 'green' => 420, 'blue' => 80]],
            [['red' => 150, 'green' => 20, 'blue' => 420]],
            [['green' => 20, 'blue' => 80]],
            [['red' => 150, 'blue' => 80]],
            [['red' => 150, 'green' => 20]],
            [['red' => null, 'green' => 20, 'blue' => 80]],
            [['red' => 150, 'green' => null, 'blue' => 80]],
            [['red' => 150, 'green' => 20, 'blue' => null]],
            [['red' => 150.42, 'green' => 20, 'blue' => 80]],
            [['red' => 150, 'green' => 20.42, 'blue' => 80]],
            [['red' => 150, 'green' => 20, 'blue' => 80.42]],
            [['red' => 'beetlejuice', 'green' => 50, 'blue' => 50]],
            [['red' => 180, 'green' => 'beetlejuice', 'blue' => 50]],
            [['red' => 180, 'green' => 50, 'blue' => 'beetlejuice']],
            [['red' => [], 'green' => 50, 'blue' => 50]],
            [['red' => 180, 'green' => [], 'blue' => 50]],
            [['red' => 180, 'green' => 50, 'blue' => []]],
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
        $rgb = new \Graphpinator\Type\Addon\RgbType();
        $rgb->validateValue($rawValue);

        self::assertSame($rgb->getName(), 'RGB');
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
}
