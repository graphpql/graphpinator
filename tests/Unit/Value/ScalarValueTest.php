<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Value;

final class ScalarValueTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [123],
            [123.123],
            ['123'],
            [true],
            [[]],
            [[123, true]],
            [new \Graphpinator\Resolver\Value\ScalarValue('inner', \Graphpinator\Type\Container\Container::String())],
            [[new \Graphpinator\Resolver\Value\ScalarValue('inner', \Graphpinator\Type\Container\Container::String())]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testSimple($rawValue): void
    {
        $type = $this->createMock(\Graphpinator\Type\Scalar\ScalarType::class);
        $type->expects($this->once())->method('validateValue')->with($rawValue);

        $value = new \Graphpinator\Resolver\Value\ScalarValue($rawValue, $type);

        self::assertSame($rawValue, $value->getRawValue());
        self::assertSame($rawValue, $value->jsonSerialize());
    }
}
