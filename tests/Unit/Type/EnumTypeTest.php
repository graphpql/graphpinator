<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type;

final class EnumTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['A'],
            ['B'],
            [null],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param string|null $rawValue
     */
    public function testValidateValue($rawValue) : void
    {
        $enum = $this->createTestEnum();
        $enum->validateResolvedValue($rawValue);

        self::assertSame($enum->getName(), 'abc');
    }

    public function invalidDataProvider() : array
    {
        return [
            [123],
            [123.123],
            ['123'],
            ['C'],
            ['D'],
            [true],
            [[]],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|float|string|bool|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $enum = $this->createTestEnum();
        $enum->validateResolvedValue($rawValue);
    }

    public function testGetItems() : void
    {
        $enum = $this->createTestEnum();

        self::assertCount(2, $enum->getItems());
        self::assertArrayHasKey('A', $enum->getItems());
        self::assertArrayHasKey('B', $enum->getItems());
        self::assertSame('A', $enum->getItems()['A']->getName());
        self::assertNull($enum->getItems()['A']->getDescription());
        self::assertFalse($enum->getItems()['A']->isDeprecated());
        self::assertNull($enum->getItems()['A']->getDeprecationReason());
        self::assertSame('B', $enum->getItems()['B']->getName());
        self::assertNull($enum->getItems()['B']->getDescription());
        self::assertFalse($enum->getItems()['B']->isDeprecated());
        self::assertNull($enum->getItems()['B']->getDeprecationReason());
    }

    public function testGetArray() : void
    {
        $items = $this->createTestEnum()->getItems()->getArray();

        self::assertSame(['A', 'B'], $items);
    }

    //@phpcs:disable SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedConstant
    protected function createTestEnum() : \Graphpinator\Type\EnumType
    {
        return new class extends \Graphpinator\Type\EnumType {
            public const ENUMA = 'a';
            public const ENUMB = 'b';

            protected const NAME = 'abc';
            protected const ENUMC = 'c';
            private const ENUMD = 'd';

            public function __construct()
            {
                parent::__construct(self::fromConstants());
            }
        };
    }

    //@phpcs:enable SlevomatCodingStandard.Classes.UnusedPrivateElements.UnusedConstant
}
