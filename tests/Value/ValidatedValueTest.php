<?php

declare(strict_types=1);

namespace Tests\Value;

final class ValidatedValueTest extends \PHPUnit\Framework\TestCase
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
            [new \PGQL\Value\Value('inner')],
            [[new \PGQL\Value\Value('inner')]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testSimple($rawValue): void
    {
        $type = $this->createMock(\PGQL\Type\Definition::class);
        $type->expects($this->once())->method('validateValue')->with($rawValue);

        $value = new \PGQL\Value\ValidatedValue($rawValue, $type);

        self::assertSame($rawValue, $value->getValue());
        self::assertSame($rawValue, $value->jsonSerialize());
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testInputable($rawValue): void
    {
        $type = $this->createMock(\PGQL\Type\Inputable::class);
        $type->expects($this->once())->method('applyDefaults')->with($rawValue)->willReturn($rawValue);
        $type->expects($this->once())->method('validateValue')->with($rawValue);

        $value = new \PGQL\Value\ValidatedValue($rawValue, $type);

        self::assertSame($type, $value->getType());
        self::assertSame($rawValue, $value->getValue());
    }
}
