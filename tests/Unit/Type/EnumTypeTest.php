<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type;

final class EnumTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['a'],
            ['b'],
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
        $value = $enum->accept(new \Graphpinator\Value\ConvertRawValueVisitor($rawValue, new \Graphpinator\Common\Path()));

        self::assertSame($enum, $value->getType());
        self::assertSame($rawValue, $value->getRawValue());
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
        $enum->accept(new \Graphpinator\Value\ConvertRawValueVisitor($rawValue, new \Graphpinator\Common\Path()));
    }

    public function testGetItems() : void
    {
        $enum = $this->createTestEnum();

        self::assertCount(2, $enum->getItems());
        self::assertArrayHasKey('a', $enum->getItems());
        self::assertArrayHasKey('b', $enum->getItems());
        self::assertSame('a', $enum->getItems()['a']->getName());
        self::assertNull($enum->getItems()['a']->getDescription());
        self::assertFalse($enum->getItems()['a']->isDeprecated());
        self::assertNull($enum->getItems()['a']->getDeprecationReason());
        self::assertSame('b', $enum->getItems()['b']->getName());
        self::assertNull($enum->getItems()['b']->getDescription());
        self::assertFalse($enum->getItems()['b']->isDeprecated());
        self::assertNull($enum->getItems()['b']->getDeprecationReason());
    }

    public function testGetArray() : void
    {
        $items = $this->createTestEnum()->getItems()->getArray();

        self::assertSame(['a', 'b'], $items);
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
