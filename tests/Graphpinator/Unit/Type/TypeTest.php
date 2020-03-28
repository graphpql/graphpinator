<?php

declare(strict_types=1);

namespace Infinityloop\Tests\Graphpinator\Unit\Type;

final class TypeTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateValue() : void
    {
        $type = self::getTestTypeAbc();

        self::assertInstanceOf(\Infinityloop\Graphpinator\Value\TypeValue::class, $type->createValue(123));
        self::assertSame(123, $type->createValue(123)->getRawValue());
    }

    public function testInstanceOf() : void
    {
        $type = self::getTestTypeAbc();

        self::assertTrue($type->isInstanceOf(self::createTestUnion()));
        self::assertTrue($type->isInstanceOf(new \Infinityloop\Graphpinator\Type\NotNullType(self::createTestUnion())));
        self::assertFalse($type->isInstanceOf(self::createTestEmptyUnion()));
        self::assertFalse($type->isInstanceOf(new \Infinityloop\Graphpinator\Type\NotNullType(self::createTestEmptyUnion())));
    }

    public static function createTestUnion() : \Infinityloop\Graphpinator\Type\UnionType
    {
        return new class extends \Infinityloop\Graphpinator\Type\UnionType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct(
                    new \Infinityloop\Graphpinator\Type\Utils\ConcreteSet([
                        TypeTest::getTestTypeAbc(),
                    ])
                );
            }
        };
    }

    public static function createTestEmptyUnion() : \Infinityloop\Graphpinator\Type\UnionType
    {
        return new class extends \Infinityloop\Graphpinator\Type\UnionType {
            protected const NAME = 'Bar';

            public function __construct()
            {
                parent::__construct(
                    new \Infinityloop\Graphpinator\Type\Utils\ConcreteSet([
                    ])
                );
            }
        };
    }

    public static function getTestTypeAbc() : \Infinityloop\Graphpinator\Type\Type
    {
        return new class extends \Infinityloop\Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(new \Infinityloop\Graphpinator\Field\FieldSet([]));
            }
        };
    }
}
