<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type;

final class TypeTest extends \PHPUnit\Framework\TestCase
{
    public const PARENT_VAL = '123';

    public static function createTestUnion() : \Graphpinator\Type\UnionType
    {
        return new class extends \Graphpinator\Type\UnionType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\ConcreteSet([
                        TypeTest::getTestTypeAbc(),
                    ]),
                );
            }
        };
    }

    public static function createTestEmptyUnion() : \Graphpinator\Type\UnionType
    {
        return new class extends \Graphpinator\Type\UnionType {
            protected const NAME = 'Bar';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\ConcreteSet([]),
                );
            }
        };
    }

    public static function getTestTypeAbc() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([]);
            }
        };
    }

    public function testCreateValue() : void
    {
        $type = self::getTestTypeAbc();

        self::assertInstanceOf(\Graphpinator\Resolver\Value\TypeValue::class, $type->createValue(123));
        self::assertSame(123, $type->createValue(123)->getRawValue());
    }

    public function testInstanceOf() : void
    {
        $type = self::getTestTypeAbc();

        self::assertTrue($type->isInstanceOf(self::createTestUnion()));
        self::assertTrue($type->isInstanceOf(new \Graphpinator\Type\NotNullType(self::createTestUnion())));
        self::assertFalse($type->isInstanceOf(self::createTestEmptyUnion()));
        self::assertFalse($type->isInstanceOf(new \Graphpinator\Type\NotNullType(self::createTestEmptyUnion())));
    }

    public function testResolveFields() : void
    {
        $type = $this->createTestType();
        $requestFields = new \Graphpinator\Normalizer\FieldSet([
            new \Graphpinator\Normalizer\Field('field1', null, new \Graphpinator\Parser\Value\NamedValueSet([])),
            new \Graphpinator\Normalizer\Field('field2', null, new \Graphpinator\Parser\Value\NamedValueSet([])),
            new \Graphpinator\Normalizer\Field('field3', null, new \Graphpinator\Parser\Value\NamedValueSet([])),
        ]);
        $parentValue = \Graphpinator\Resolver\FieldResult::fromRaw(\Graphpinator\Type\Container\Container::String(), self::PARENT_VAL);
        $result = $type->resolve($requestFields, $parentValue);

        self::assertCount(3, $result);

        foreach (['field1' => 'fieldValue', 'field2' => false, 'field3' => null] as $name => $value) {
            self::assertArrayHasKey($name, $result);
            self::assertSame($value, $result[$name]->getRawValue());
        }
    }

    public function testGetFields() : void
    {
        $type = $this->createTestType();

        self::assertCount(3, $type->getFields());
    }

    protected function createTestType() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field1',
                        \Graphpinator\Type\Container\Container::String(),
                        static function ($parentValue, \Graphpinator\Resolver\ArgumentValueSet $arguments) {
                            TypeTest::assertSame(TypeTest::PARENT_VAL, $parentValue);
                            TypeTest::assertCount(0, $arguments);

                            return 'fieldValue';
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'field2',
                        \Graphpinator\Type\Container\Container::Boolean(),
                        static function ($parentValue, \Graphpinator\Resolver\ArgumentValueSet $arguments) {
                            TypeTest::assertSame(TypeTest::PARENT_VAL, $parentValue);
                            TypeTest::assertCount(0, $arguments);

                            return false;
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'field3',
                        \Graphpinator\Type\Container\Container::Int(),
                        static function ($parentValue, \Graphpinator\Resolver\ArgumentValueSet $arguments) {
                            TypeTest::assertSame(TypeTest::PARENT_VAL, $parentValue);
                            TypeTest::assertCount(0, $arguments);

                            return null;
                        },
                    ),
                ]);
            }
        };
    }
}
