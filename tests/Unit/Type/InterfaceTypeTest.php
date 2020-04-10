<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Type;

final class InterfaceTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $interface = self::createTestInterface();
        $parent = self::createTestParentInterface();

        self::assertTrue($interface->isInstanceOf($interface));
        self::assertTrue($interface->isInstanceOf(new \Graphpinator\Type\NotNullType($interface)));
        self::assertTrue($interface->isInstanceOf($parent));
        self::assertTrue($interface->isInstanceOf(new \Graphpinator\Type\NotNullType($parent)));
        self::assertFalse($parent->isInstanceOf($interface));
        self::assertFalse($parent->isInstanceOf(new \Graphpinator\Type\NotNullType($interface)));
        self::assertFalse($interface->isImplementedBy(self::getTestTypeAbc()));
        self::assertFalse($interface->isImplementedBy(new \Graphpinator\Type\NotNullType(self::getTestTypeAbc())));
        self::assertFalse($parent->isImplementedBy(self::getTestTypeAbc()));
        self::assertFalse($parent->isImplementedBy(new \Graphpinator\Type\NotNullType(self::getTestTypeAbc())));
        self::assertTrue($interface->isImplementedBy(self::getTestTypeXyz()));
        self::assertTrue($interface->isImplementedBy(new \Graphpinator\Type\NotNullType(self::getTestTypeXyz())));
        self::assertTrue($parent->isImplementedBy(self::getTestTypeXyz()));
        self::assertTrue($parent->isImplementedBy(new \Graphpinator\Type\NotNullType(self::getTestTypeXyz())));
        self::assertFalse($interface->isImplementedBy(self::getTestTypeZzz()));
        self::assertFalse($interface->isImplementedBy(new \Graphpinator\Type\NotNullType(self::getTestTypeZzz())));
        self::assertTrue($parent->isImplementedBy(self::getTestTypeZzz()));
        self::assertTrue($parent->isImplementedBy(new \Graphpinator\Type\NotNullType(self::getTestTypeZzz())));
    }

    public function testMissingField() : void
    {
        $this->expectException(\Exception::class);
        self::getInvalidType1()->getFields();
    }

    public function testIncompatibleType() : void
    {
        $this->expectException(\Exception::class);
        self::getInvalidType2()->getFields();
    }

    public static function getInvalidType1() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createTestInterface(),
                    ])
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([]);
            }
        };
    }

    public static function getInvalidType2() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createTestInterface(),
                    ])
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField('name', \Graphpinator\Type\Container\Container::String(), function (){}),
                    new \Graphpinator\Field\ResolvableField('otherName', \Graphpinator\Type\Container\Container::Boolean(), function (){}),
                ]);
            }
        };
    }

    public static function createTestInterface() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createTestParentInterface(),
                    ])
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field('name', \Graphpinator\Type\Container\Container::String()),
                    new \Graphpinator\Field\Field('otherName', \Graphpinator\Type\Container\Container::Int()->notNull()),
                ]);
            }
        };
    }

    public static function createTestParentInterface() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'Bar';

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field('name', \Graphpinator\Type\Container\Container::String())
                ]);
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

    public static function getTestTypeXyz() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Xyz';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createTestInterface(),
                    ])
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField('name', \Graphpinator\Type\Container\Container::String(), function (){}),
                    new \Graphpinator\Field\ResolvableField('otherName', \Graphpinator\Type\Container\Container::Int(), function (){}),
                ]);
            }
        };
    }

    public static function getTestTypeZzz() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Zzz';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createTestParentInterface(),
                    ])
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField('name', \Graphpinator\Type\Container\Container::String(), function (){}),
                ]);
            }
        };
    }
}
