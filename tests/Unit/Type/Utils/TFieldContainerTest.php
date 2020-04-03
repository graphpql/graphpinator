<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Type\Utils;

final class TFieldContainerTest extends \PHPUnit\Framework\TestCase
{
    public const PARENT_VAL = '123';

    public function invalidDataProvider() : array
    {
        return [
            // no fields requested
            [
                null,
                \Graphpinator\Field\ResolveResult::fromRaw(\Graphpinator\Type\Scalar\ScalarType::String(), self::PARENT_VAL)
            ],
            // field which do not exist
            [
                new \Graphpinator\Request\FieldSet([
                    new \Graphpinator\Request\Field('field0')
                ]),
                \Graphpinator\Field\ResolveResult::fromRaw(\Graphpinator\Type\Scalar\ScalarType::String(), self::PARENT_VAL)
            ],
            // argument which do not exist
            [
                new \Graphpinator\Request\FieldSet([
                    new \Graphpinator\Request\Field('field1', null, null, new \Graphpinator\Value\GivenValueSet([new \Graphpinator\Parser\Value\NamedValue('val', 'arg1')]))
                ]),
                \Graphpinator\Field\ResolveResult::fromRaw(\Graphpinator\Type\Scalar\ScalarType::String(), self::PARENT_VAL)
            ],
            // inner fields on leaf type
            [
                new \Graphpinator\Request\FieldSet([
                    new \Graphpinator\Request\Field('field1', null, new \Graphpinator\Request\FieldSet([new \Graphpinator\Request\Field('innerField')]))
                ]),
                \Graphpinator\Field\ResolveResult::fromRaw(\Graphpinator\Type\Scalar\ScalarType::String(), self::PARENT_VAL)
            ],
        ];
    }

    public function testResolveFields(): void
    {
        $requestFields = new \Graphpinator\Request\FieldSet([
            new \Graphpinator\Request\Field('field1'),
            new \Graphpinator\Request\Field('field2'),
            new \Graphpinator\Request\Field('field3'),
        ]);
        $parentValue = \Graphpinator\Field\ResolveResult::fromRaw(\Graphpinator\Type\Scalar\ScalarType::String(), self::PARENT_VAL);

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
        $requestFields = new \Graphpinator\Request\FieldSet([
            new \Graphpinator\Request\Field('field1', null, null, null, \Graphpinator\Type\Scalar\ScalarType::String()),
            new \Graphpinator\Request\Field('field2', null, null, null, \Graphpinator\Type\Scalar\ScalarType::Int()),
            new \Graphpinator\Request\Field('field3'),
        ]);
        $parentValue = \Graphpinator\Field\ResolveResult::fromRaw(\Graphpinator\Type\Scalar\ScalarType::String(), self::PARENT_VAL);

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
    public function testResolveFieldsInvalid(?\Graphpinator\Request\FieldSet $requestFields, \Graphpinator\Field\ResolveResult $parentValue): void
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

    protected function createTestType() : \Graphpinator\Type\Utils\FieldContainer
    {
        return new class implements \Graphpinator\Type\Utils\FieldContainer {
            use \Graphpinator\Type\Utils\TFieldContainer;

            public function __construct()
            {
                $this->fields = new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field(
                        'field1',
                        \Graphpinator\Type\Scalar\ScalarType::String(),
                        static function ($parentValue, \Graphpinator\Value\ValidatedValueSet $arguments) {
                            TFieldContainerTest::assertSame(TFieldContainerTest::PARENT_VAL, $parentValue);

                            return 'fieldValue';
                        }),
                    new \Graphpinator\Field\Field(
                        'field2',
                        \Graphpinator\Type\Scalar\ScalarType::Boolean(),
                        static function ($parentValue, \Graphpinator\Value\ValidatedValueSet $arguments) {
                            TFieldContainerTest::assertSame(TFieldContainerTest::PARENT_VAL, $parentValue);

                            return false;
                        }),
                    new \Graphpinator\Field\Field(
                        'field3',
                        \Graphpinator\Type\Scalar\ScalarType::Int(),
                        static function ($parentValue, \Graphpinator\Value\ValidatedValueSet $arguments) {
                            TFieldContainerTest::assertSame(TFieldContainerTest::PARENT_VAL, $parentValue);

                            return null;
                        }),
                ]);
            }
        };
    }
}
