<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Type\Scalar;

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

    public function invalidDataProvider() : array
    {
        return [
            [123],
            [123.123],
            ['123'],
            ['c'],
            ['d'],
            [true],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testValidateValue($rawValue): void
    {
        $enum = $this->createTestEnum();
        $enum->validateValue($rawValue);

        self::assertSame($enum->getName(), 'abc');
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testValidateValueInvalid($rawValue): void
    {
        $this->expectException(\Exception::class);

        $enum = $this->createTestEnum();
        $enum->validateValue($rawValue);
    }

    public function testGetAll(): void
    {
        $enum = $this->createTestEnum();

        self::assertSame($enum->getAll(), ['a', 'b']);
    }

    protected function createTestEnum() : \Graphpinator\Type\Scalar\EnumType
    {
        return new class extends \Graphpinator\Type\Scalar\EnumType {
            protected const NAME = 'abc';

            public const ENUMA = 'a';
            public const ENUMB = 'b';
            protected const ENUMC = 'c';
            private const ENUMD = 'd';

            public function __construct()
            {
                parent::__construct(self::fromConstants());
            }
        };
    }
}
