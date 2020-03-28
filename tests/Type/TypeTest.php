<?php

declare(strict_types=1);

namespace Tests\Type;

final class TypeTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateValue() : void
    {
        $type = self::getTestTypeAbc();

        self::assertInstanceOf(\PGQL\Value\TypeValue::class, $type->createValue(123));
        self::assertSame(123, $type->createValue(123)->getRawValue());
    }

    public function testInstanceOf() : void
    {
        $type = self::getTestTypeAbc();

        self::assertTrue($type->isInstanceOf(self::createTestUnion()));
        self::assertTrue($type->isInstanceOf(new \PGQL\Type\NotNullType(self::createTestUnion())));
        self::assertFalse($type->isInstanceOf(self::createTestEmptyUnion()));
        self::assertFalse($type->isInstanceOf(new \PGQL\Type\NotNullType(self::createTestEmptyUnion())));
    }

    public static function createTestUnion() : \PGQL\Type\UnionType
    {
        return new class extends \PGQL\Type\UnionType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct(
                    new \PGQL\Type\Utils\ConcreteSet([
                        TypeTest::getTestTypeAbc(),
                    ])
                );
            }
        };
    }

    public static function createTestEmptyUnion() : \PGQL\Type\UnionType
    {
        return new class extends \PGQL\Type\UnionType {
            protected const NAME = 'Bar';

            public function __construct()
            {
                parent::__construct(
                    new \PGQL\Type\Utils\ConcreteSet([
                    ])
                );
            }
        };
    }

    public static function getTestTypeAbc() : \PGQL\Type\Type
    {
        return new class extends \PGQL\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(new \PGQL\Field\FieldSet([]));
            }
        };
    }
}
