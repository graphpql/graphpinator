<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Type;

final class TypeTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateValue() : void
    {
        $type = self::getTestTypeAbc();

        self::assertInstanceOf(\Graphpinator\Value\TypeValue::class, $type->createValue(123));
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

    public static function createTestUnion() : \Graphpinator\Type\UnionType
    {
        return new class extends \Graphpinator\Type\UnionType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Type\Utils\ConcreteSet([
                        TypeTest::getTestTypeAbc(),
                    ])
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
                    new \Graphpinator\Type\Utils\ConcreteSet([
                    ])
                );
            }
        };
    }

    public static function getTestTypeAbc() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Field\FieldSet([]));
            }
        };
    }
}
