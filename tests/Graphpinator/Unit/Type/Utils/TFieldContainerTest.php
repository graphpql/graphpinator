<?php

declare(strict_types=1);

namespace Infinityloop\Tests\Graphpinator\Unit\Type\Utils;

final class TFieldContainerTest extends \PHPUnit\Framework\TestCase
{
    public const PARENT_VAL = '123';

    public function invalidDataProvider() : array
    {
        return [
            // no fields requested
            [
                null,
                \Infinityloop\Graphpinator\Field\ResolveResult::fromRaw(\Infinityloop\Graphpinator\Type\Scalar\ScalarType::String(), self::PARENT_VAL)
            ],
            // field which do not exist
            [
                new \Infinityloop\Graphpinator\Parser\RequestFieldSet([
                    new \Infinityloop\Graphpinator\Parser\RequestField('field0')
                ]),
                \Infinityloop\Graphpinator\Field\ResolveResult::fromRaw(\Infinityloop\Graphpinator\Type\Scalar\ScalarType::String(), self::PARENT_VAL)
            ],
            // argument which do not exist
            [
                new \Infinityloop\Graphpinator\Parser\RequestFieldSet([
                    new \Infinityloop\Graphpinator\Parser\RequestField('field1', null, null, new \Infinityloop\Graphpinator\Value\GivenValueSet([new \Infinityloop\Graphpinator\Value\GivenValue('val', 'arg1')]))
                ]),
                \Infinityloop\Graphpinator\Field\ResolveResult::fromRaw(\Infinityloop\Graphpinator\Type\Scalar\ScalarType::String(), self::PARENT_VAL)
            ],
            // inner fields on leaf type
            [
                new \Infinityloop\Graphpinator\Parser\RequestFieldSet([
                    new \Infinityloop\Graphpinator\Parser\RequestField('field1', new \Infinityloop\Graphpinator\Parser\RequestFieldSet([new \Infinityloop\Graphpinator\Parser\RequestField('innerField')]))
                ]),
                \Infinityloop\Graphpinator\Field\ResolveResult::fromRaw(\Infinityloop\Graphpinator\Type\Scalar\ScalarType::String(), self::PARENT_VAL)
            ],
        ];
    }

    public function testResolveFields(): void
    {
        $requestFields = new \Infinityloop\Graphpinator\Parser\RequestFieldSet([
            new \Infinityloop\Graphpinator\Parser\RequestField('field1'),
            new \Infinityloop\Graphpinator\Parser\RequestField('field2'),
            new \Infinityloop\Graphpinator\Parser\RequestField('field3'),
        ]);
        $parentValue = \Infinityloop\Graphpinator\Field\ResolveResult::fromRaw(\Infinityloop\Graphpinator\Type\Scalar\ScalarType::String(), self::PARENT_VAL);

        $type = $this->createTestType();
        $result = $type->resolveFields($requestFields, $parentValue);

        self::assertCount(3, $result);

        foreach (['field1' => 'fieldValue', 'field2' => false, 'field3' => null] as $name => $value) {
            self::assertArrayHasKey($name, $result);
            self::assertSame($value, $result[$name]->getRawValue());
        }
    }

    public function testResolveFieldsIgnore(): void
    {
        $requestFields = new \Infinityloop\Graphpinator\Parser\RequestFieldSet([
            new \Infinityloop\Graphpinator\Parser\RequestField('field1', null, \Infinityloop\Graphpinator\Type\Scalar\ScalarType::String()),
            new \Infinityloop\Graphpinator\Parser\RequestField('field2', null, \Infinityloop\Graphpinator\Type\Scalar\ScalarType::Int()),
            new \Infinityloop\Graphpinator\Parser\RequestField('field3'),
        ]);
        $parentValue = \Infinityloop\Graphpinator\Field\ResolveResult::fromRaw(\Infinityloop\Graphpinator\Type\Scalar\ScalarType::String(), self::PARENT_VAL);

        $type = $this->createTestType();
        $result = $type->resolveFields($requestFields, $parentValue);

        self::assertCount(2, $result);

        foreach (['field1' => 'fieldValue', 'field3' => null] as $name => $value) {
            self::assertArrayHasKey($name, $result);
            self::assertSame($value, $result[$name]->getRawValue());
        }
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testResolveFieldsInvalid(?\Infinityloop\Graphpinator\Parser\RequestFieldSet $requestFields, \Infinityloop\Graphpinator\Field\ResolveResult $parentValue): void
    {
        $this->expectException(\Exception::class);

        $type = $this->createTestType();
        $type->resolveFields($requestFields, $parentValue);
    }

    public function testGetFields(): void
    {
        $type = $this->createTestType();

        self::assertCount(3, $type->getFields());
    }

    protected function createTestType() : \Infinityloop\Graphpinator\Type\Utils\FieldContainer
    {
        return new class implements \Infinityloop\Graphpinator\Type\Utils\FieldContainer {
            use \Infinityloop\Graphpinator\Type\Utils\TFieldContainer;

            public function __construct()
            {
                $this->fields = new \Infinityloop\Graphpinator\Field\FieldSet([
                    new \Infinityloop\Graphpinator\Field\Field(
                        'field1',
                        \Infinityloop\Graphpinator\Type\Scalar\ScalarType::String(),
                        static function ($parentValue, \Infinityloop\Graphpinator\Value\ValidatedValueSet $arguments) {
                            TFieldContainerTest::assertSame(TFieldContainerTest::PARENT_VAL, $parentValue);

                            return 'fieldValue';
                        }),
                    new \Infinityloop\Graphpinator\Field\Field(
                        'field2',
                        \Infinityloop\Graphpinator\Type\Scalar\ScalarType::Boolean(),
                        static function ($parentValue, \Infinityloop\Graphpinator\Value\ValidatedValueSet $arguments) {
                            TFieldContainerTest::assertSame(TFieldContainerTest::PARENT_VAL, $parentValue);

                            return false;
                        }),
                    new \Infinityloop\Graphpinator\Field\Field(
                        'field3',
                        \Infinityloop\Graphpinator\Type\Scalar\ScalarType::Int(),
                        static function ($parentValue, \Infinityloop\Graphpinator\Value\ValidatedValueSet $arguments) {
                            TFieldContainerTest::assertSame(TFieldContainerTest::PARENT_VAL, $parentValue);

                            return null;
                        }),
                ]);
            }
        };
    }
}
