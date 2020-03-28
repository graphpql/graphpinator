<?php

declare(strict_types=1);

namespace Tests\Type;

final class UnionTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $union = self::createTestUnion();

        self::assertTrue($union->isInstanceOf($union));
        self::assertTrue($union->isInstanceOf(new \PGQL\Type\NotNullType($union)));
        self::assertFalse($union->isInstanceOf(self::getTestTypeZzz()));
        self::assertFalse($union->isInstanceOf(new \PGQL\Type\NotNullType(self::getTestTypeZzz())));
        self::assertTrue($union->isImplementedBy(self::getTestTypeXyz()));
        self::assertTrue($union->isImplementedBy(new \PGQL\Type\NotNullType(self::getTestTypeXyz())));
        self::assertTrue($union->isImplementedBy(self::getTestTypeZzz()));
        self::assertTrue($union->isImplementedBy(new \PGQL\Type\NotNullType(self::getTestTypeZzz())));
        self::assertFalse($union->isImplementedBy(self::getTestTypeAbc()));
        self::assertFalse($union->isImplementedBy(new \PGQL\Type\NotNullType(self::getTestTypeAbc())));
    }

    public static function createTestUnion() : \PGQL\Type\UnionType
    {
        return new class extends \PGQL\Type\UnionType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct(
                    new \PGQL\Type\Utils\ConcreteSet([
                        UnionTypeTest::getTestTypeXyz(),
                        UnionTypeTest::getTestTypeZzz(),
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

    public static function getTestTypeXyz() : \PGQL\Type\Type
    {
        return new class extends \PGQL\Type\Type {
            protected const NAME = 'Xyz';

            public function __construct()
            {
                parent::__construct(new \PGQL\Field\FieldSet([]));
            }
        };
    }

    public static function getTestTypeZzz() : \PGQL\Type\Type
    {
        return new class extends \PGQL\Type\Type {
            protected const NAME = 'Zzz';

            public function __construct()
            {
                parent::__construct(new \PGQL\Field\FieldSet([]));
            }
        };
    }
}
