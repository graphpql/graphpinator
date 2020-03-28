<?php

declare(strict_types=1);

namespace Tests\Type;

final class InterfaceTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $interface = self::createTestInterface();
        $parent = self::createTestParentInterface();

        self::assertTrue($interface->isInstanceOf($interface));
        self::assertTrue($interface->isInstanceOf(new \PGQL\Type\NotNullType($interface)));
        self::assertTrue($interface->isInstanceOf($parent));
        self::assertTrue($interface->isInstanceOf(new \PGQL\Type\NotNullType($parent)));
        self::assertFalse($parent->isInstanceOf($interface));
        self::assertFalse($parent->isInstanceOf(new \PGQL\Type\NotNullType($interface)));
        self::assertFalse($interface->isImplementedBy(self::getTestTypeAbc()));
        self::assertFalse($interface->isImplementedBy(new \PGQL\Type\NotNullType(self::getTestTypeAbc())));
        self::assertFalse($parent->isImplementedBy(self::getTestTypeAbc()));
        self::assertFalse($parent->isImplementedBy(new \PGQL\Type\NotNullType(self::getTestTypeAbc())));
        self::assertTrue($interface->isImplementedBy(self::getTestTypeXyz()));
        self::assertTrue($interface->isImplementedBy(new \PGQL\Type\NotNullType(self::getTestTypeXyz())));
        self::assertTrue($parent->isImplementedBy(self::getTestTypeXyz()));
        self::assertTrue($parent->isImplementedBy(new \PGQL\Type\NotNullType(self::getTestTypeXyz())));
        self::assertFalse($interface->isImplementedBy(self::getTestTypeZzz()));
        self::assertFalse($interface->isImplementedBy(new \PGQL\Type\NotNullType(self::getTestTypeZzz())));
        self::assertTrue($parent->isImplementedBy(self::getTestTypeZzz()));
        self::assertTrue($parent->isImplementedBy(new \PGQL\Type\NotNullType(self::getTestTypeZzz())));
    }

    public static function createTestInterface() : \PGQL\Type\InterfaceType
    {
        return new class extends \PGQL\Type\InterfaceType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct(
                    new \PGQL\Field\FieldSet([]),
                    new \PGQL\Type\Utils\InterfaceSet([
                        InterfaceTypeTest::createTestParentInterface(),
                    ])
                );
            }
        };
    }

    public static function createTestParentInterface() : \PGQL\Type\InterfaceType
    {
        return new class extends \PGQL\Type\InterfaceType {
            protected const NAME = 'Bar';

            public function __construct()
            {
                parent::__construct(
                    new \PGQL\Field\FieldSet([]),
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
                parent::__construct(
                    new \PGQL\Field\FieldSet([])
                );
            }
        };
    }

    public static function getTestTypeXyz() : \PGQL\Type\Type
    {
        return new class extends \PGQL\Type\Type {
            protected const NAME = 'Xyz';

            public function __construct()
            {
                parent::__construct(
                    new \PGQL\Field\FieldSet([]),
                    new \PGQL\Type\Utils\InterfaceSet([
                        InterfaceTypeTest::createTestInterface(),
                    ])
                );
            }
        };
    }

    public static function getTestTypeZzz() : \PGQL\Type\Type
    {
        return new class extends \PGQL\Type\Type {
            protected const NAME = 'Zzz';

            public function __construct()
            {
                parent::__construct(
                    new \PGQL\Field\FieldSet([]),
                    new \PGQL\Type\Utils\InterfaceSet([
                        InterfaceTypeTest::createTestParentInterface(),
                    ])
                );
            }
        };
    }
}
