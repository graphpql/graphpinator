<?php

declare(strict_types=1);

namespace Tests\Type\Scalar;

final class TFieldContainerTest extends \PHPUnit\Framework\TestCase
{
    public const PARENT_VAL = '123';

    public function invalidDataProvider() : array
    {
        return [
            // no fields requested
            [
                null,
                \PGQL\Field\ResolveResult::fromRaw(\PGQL\Type\Scalar\ScalarType::String(), self::PARENT_VAL)
            ],
            // field which do not exist
            [
                [new \PGQL\RequestField('field0')],
                \PGQL\Field\ResolveResult::fromRaw(\PGQL\Type\Scalar\ScalarType::String(), self::PARENT_VAL)
            ],
            // argument which do not exist
            [
                [new \PGQL\RequestField('field1', null, null, new \PGQL\Value\GivenValueSet([new \PGQL\Value\GivenValue('val', 'arg1')]))],
                \PGQL\Field\ResolveResult::fromRaw(\PGQL\Type\Scalar\ScalarType::String(), self::PARENT_VAL)
            ],
            // inner fields on leaf type
            [
                [new \PGQL\RequestField('field1', [new \PGQL\RequestField('innerField')])],
                \PGQL\Field\ResolveResult::fromRaw(\PGQL\Type\Scalar\ScalarType::String(), self::PARENT_VAL)
            ],
        ];
    }

    public function testResolveFields(): void
    {
        $requestFields = [
            new \PGQL\RequestField('field1'),
            new \PGQL\RequestField('field2'),
            new \PGQL\RequestField('field3'),
        ];
        $parentValue = \PGQL\Field\ResolveResult::fromRaw(\PGQL\Type\Scalar\ScalarType::String(), self::PARENT_VAL);

        $type = $this->createTestType();
        $result = $type->resolveFields($requestFields, $parentValue);

        self::assertCount(3, $result);

        foreach (['field1' => 'fieldValue', 'field2' => false, 'field3' => null] as $name => $value) {
            self::assertArrayHasKey($name, $result);
            self::assertSame($value, $result[$name]->getValue());
        }
    }

    public function testResolveFieldsIgnore(): void
    {
        $requestFields = [
            new \PGQL\RequestField('field1', null, 'String'),
            new \PGQL\RequestField('field2', null, 'Int'),
            new \PGQL\RequestField('field3'),
        ];
        $parentValue = \PGQL\Field\ResolveResult::fromRaw(\PGQL\Type\Scalar\ScalarType::String(), self::PARENT_VAL);

        $type = $this->createTestType();
        $result = $type->resolveFields($requestFields, $parentValue);

        self::assertCount(2, $result);

        foreach (['field1' => 'fieldValue', 'field3' => null] as $name => $value) {
            self::assertArrayHasKey($name, $result);
            self::assertSame($value, $result[$name]->getValue());
        }
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testResolveFieldsInvalid(?array $requestFields, \PGQL\Field\ResolveResult $parentValue): void
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

    protected function createTestType() : \PGQL\Type\Utils\FieldContainer
    {
        return new class implements \PGQL\Type\Utils\FieldContainer {
            use \PGQL\Type\Utils\TFieldContainer;

            public function __construct()
            {
                $this->fields = new \PGQL\Field\FieldSet([
                    new \PGQL\Field\Field(
                        'field1',
                        \PGQL\Type\Scalar\ScalarType::String(),
                        static function ($parentValue, \PGQL\Value\ValidatedValueSet $arguments) {
                            TFieldContainerTest::assertSame(TFieldContainerTest::PARENT_VAL, $parentValue);

                            return 'fieldValue';
                        }),
                    new \PGQL\Field\Field(
                        'field2',
                        \PGQL\Type\Scalar\ScalarType::Boolean(),
                        static function ($parentValue, \PGQL\Value\ValidatedValueSet $arguments) {
                            TFieldContainerTest::assertSame(TFieldContainerTest::PARENT_VAL, $parentValue);

                            return false;
                        }),
                    new \PGQL\Field\Field(
                        'field3',
                        \PGQL\Type\Scalar\ScalarType::Int(),
                        static function ($parentValue, \PGQL\Value\ValidatedValueSet $arguments) {
                            TFieldContainerTest::assertSame(TFieldContainerTest::PARENT_VAL, $parentValue);

                            return null;
                        }),
                ]);
            }
        };
    }
}
