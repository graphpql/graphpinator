<?php

declare(strict_types=1);

namespace Infinityloop\Tests\Graphpinator\Unit\Type;

final class UnionTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $union = self::createTestUnion();

        self::assertTrue($union->isInstanceOf($union));
        self::assertTrue($union->isInstanceOf(new \Infinityloop\Graphpinator\Type\NotNullType($union)));
        self::assertFalse($union->isInstanceOf(self::getTestTypeZzz()));
        self::assertFalse($union->isInstanceOf(new \Infinityloop\Graphpinator\Type\NotNullType(self::getTestTypeZzz())));
        self::assertTrue($union->isImplementedBy(self::getTestTypeXyz()));
        self::assertTrue($union->isImplementedBy(new \Infinityloop\Graphpinator\Type\NotNullType(self::getTestTypeXyz())));
        self::assertTrue($union->isImplementedBy(self::getTestTypeZzz()));
        self::assertTrue($union->isImplementedBy(new \Infinityloop\Graphpinator\Type\NotNullType(self::getTestTypeZzz())));
        self::assertFalse($union->isImplementedBy(self::getTestTypeAbc()));
        self::assertFalse($union->isImplementedBy(new \Infinityloop\Graphpinator\Type\NotNullType(self::getTestTypeAbc())));
    }

    public static function createTestUnion() : \Infinityloop\Graphpinator\Type\UnionType
    {
        return new class extends \Infinityloop\Graphpinator\Type\UnionType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct(
                    new \Infinityloop\Graphpinator\Type\Utils\ConcreteSet([
                        UnionTypeTest::getTestTypeXyz(),
                        UnionTypeTest::getTestTypeZzz(),
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

    public static function getTestTypeXyz() : \Infinityloop\Graphpinator\Type\Type
    {
        return new class extends \Infinityloop\Graphpinator\Type\Type {
            protected const NAME = 'Xyz';

            public function __construct()
            {
                parent::__construct(new \Infinityloop\Graphpinator\Field\FieldSet([]));
            }
        };
    }

    public static function getTestTypeZzz() : \Infinityloop\Graphpinator\Type\Type
    {
        return new class extends \Infinityloop\Graphpinator\Type\Type {
            protected const NAME = 'Zzz';

            public function __construct()
            {
                parent::__construct(new \Infinityloop\Graphpinator\Field\FieldSet([]));
            }
        };
    }
}
