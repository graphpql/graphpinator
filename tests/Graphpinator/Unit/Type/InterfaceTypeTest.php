<?php

declare(strict_types=1);

namespace Infinityloop\Tests\Graphpinator\Unit\Type;

final class InterfaceTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $interface = self::createTestInterface();
        $parent = self::createTestParentInterface();

        self::assertTrue($interface->isInstanceOf($interface));
        self::assertTrue($interface->isInstanceOf(new \Infinityloop\Graphpinator\Type\NotNullType($interface)));
        self::assertTrue($interface->isInstanceOf($parent));
        self::assertTrue($interface->isInstanceOf(new \Infinityloop\Graphpinator\Type\NotNullType($parent)));
        self::assertFalse($parent->isInstanceOf($interface));
        self::assertFalse($parent->isInstanceOf(new \Infinityloop\Graphpinator\Type\NotNullType($interface)));
        self::assertFalse($interface->isImplementedBy(self::getTestTypeAbc()));
        self::assertFalse($interface->isImplementedBy(new \Infinityloop\Graphpinator\Type\NotNullType(self::getTestTypeAbc())));
        self::assertFalse($parent->isImplementedBy(self::getTestTypeAbc()));
        self::assertFalse($parent->isImplementedBy(new \Infinityloop\Graphpinator\Type\NotNullType(self::getTestTypeAbc())));
        self::assertTrue($interface->isImplementedBy(self::getTestTypeXyz()));
        self::assertTrue($interface->isImplementedBy(new \Infinityloop\Graphpinator\Type\NotNullType(self::getTestTypeXyz())));
        self::assertTrue($parent->isImplementedBy(self::getTestTypeXyz()));
        self::assertTrue($parent->isImplementedBy(new \Infinityloop\Graphpinator\Type\NotNullType(self::getTestTypeXyz())));
        self::assertFalse($interface->isImplementedBy(self::getTestTypeZzz()));
        self::assertFalse($interface->isImplementedBy(new \Infinityloop\Graphpinator\Type\NotNullType(self::getTestTypeZzz())));
        self::assertTrue($parent->isImplementedBy(self::getTestTypeZzz()));
        self::assertTrue($parent->isImplementedBy(new \Infinityloop\Graphpinator\Type\NotNullType(self::getTestTypeZzz())));
    }

    public static function createTestInterface() : \Infinityloop\Graphpinator\Type\InterfaceType
    {
        return new class extends \Infinityloop\Graphpinator\Type\InterfaceType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct(
                    new \Infinityloop\Graphpinator\Field\FieldSet([]),
                    new \Infinityloop\Graphpinator\Type\Utils\InterfaceSet([
                        InterfaceTypeTest::createTestParentInterface(),
                    ])
                );
            }
        };
    }

    public static function createTestParentInterface() : \Infinityloop\Graphpinator\Type\InterfaceType
    {
        return new class extends \Infinityloop\Graphpinator\Type\InterfaceType {
            protected const NAME = 'Bar';

            public function __construct()
            {
                parent::__construct(
                    new \Infinityloop\Graphpinator\Field\FieldSet([]),
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
                parent::__construct(
                    new \Infinityloop\Graphpinator\Field\FieldSet([])
                );
            }
        };
    }

    public static function getTestTypeXyz() : \Infinityloop\Graphpinator\Type\Type
    {
        return new class extends \Infinityloop\Graphpinator\Type\Type {
            protected const NAME = 'Xyz';

            public function __construct()
            {
                parent::__construct(
                    new \Infinityloop\Graphpinator\Field\FieldSet([]),
                    new \Infinityloop\Graphpinator\Type\Utils\InterfaceSet([
                        InterfaceTypeTest::createTestInterface(),
                    ])
                );
            }
        };
    }

    public static function getTestTypeZzz() : \Infinityloop\Graphpinator\Type\Type
    {
        return new class extends \Infinityloop\Graphpinator\Type\Type {
            protected const NAME = 'Zzz';

            public function __construct()
            {
                parent::__construct(
                    new \Infinityloop\Graphpinator\Field\FieldSet([]),
                    new \Infinityloop\Graphpinator\Type\Utils\InterfaceSet([
                        InterfaceTypeTest::createTestParentInterface(),
                    ])
                );
            }
        };
    }
}
