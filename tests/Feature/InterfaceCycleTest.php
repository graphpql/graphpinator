<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class InterfaceCycleTest extends \PHPUnit\Framework\TestCase
{
    private static ?\Graphpinator\Type\InterfaceType $interfaceA = null;
    private static ?\Graphpinator\Type\InterfaceType $interfaceB = null;
    private static ?\Graphpinator\Type\InterfaceType $interfaceC = null;

    public static function getInterfaceB() : \Graphpinator\Type\InterfaceType
    {
        if (self::$interfaceB instanceof \Graphpinator\Type\InterfaceType) {
            return self::$interfaceB;
        }

        self::$interfaceB = new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'BInterface';

            public function __construct()
            {
                parent::__construct();
            }

            public function initImplements() : void
            {
                $this->implements[] = InterfaceCycleTest::getInterfaceA();
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    \Graphpinator\Field\Field::create(
                        'fieldInt',
                        \Graphpinator\Container\Container::Int(),
                    ),
                ]);
            }
        };

        self::$interfaceB->initImplements();

        return self::$interfaceB;
    }

    public static function getInterfaceC() : \Graphpinator\Type\InterfaceType
    {
        if (self::$interfaceC instanceof \Graphpinator\Type\InterfaceType) {
            return self::$interfaceC;
        }

        self::$interfaceC = new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'CInterface';

            public function __construct()
            {
                parent::__construct();
            }

            public function initImplements() : void
            {
                $this->implements[] = InterfaceCycleTest::getInterfaceB();
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    \Graphpinator\Field\Field::create(
                        'fieldInt',
                        \Graphpinator\Container\Container::Int(),
                    ),
                ]);
            }
        };

        self::$interfaceC->initImplements();

        return self::$interfaceC;
    }

    public static function getInterfaceA() : \Graphpinator\Type\InterfaceType
    {
        if (self::$interfaceA instanceof \Graphpinator\Type\InterfaceType) {
            return self::$interfaceA;
        }

        self::$interfaceA = new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'InterfaceA';

            public function __construct()
            {
                parent::__construct();
            }

            public function initImplements() : void
            {
                $this->implements[] = InterfaceCycleTest::getInterfaceC();
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    \Graphpinator\Field\Field::create(
                        'fieldInt',
                        \Graphpinator\Container\Container::Int(),
                    ),
                ]);
            }
        };

        self::$interfaceA->initImplements();

        return self::$interfaceA;
    }

    public function testInvalidA() : void
    {
        self::$interfaceA = null;
        self::$interfaceB = null;
        self::$interfaceC = null;

        $this->expectException(\Graphpinator\Typesystem\Exception\InterfaceCycle::class);
        $this->expectDeprecationMessage(\Graphpinator\Typesystem\Exception\InterfaceCycle::MESSAGE);

        self::getInterfaceA()->getFields();
    }

    public function testInvalidB() : void
    {
        self::$interfaceA = null;
        self::$interfaceB = null;
        self::$interfaceC = null;

        $this->expectException(\Graphpinator\Typesystem\Exception\InterfaceCycle::class);
        $this->expectDeprecationMessage(\Graphpinator\Typesystem\Exception\InterfaceCycle::MESSAGE);

        self::getInterfaceB()->getFields();
    }

    public function testInvalidC() : void
    {
        self::$interfaceA = null;
        self::$interfaceB = null;
        self::$interfaceC = null;

        $this->expectException(\Graphpinator\Typesystem\Exception\InterfaceCycle::class);
        $this->expectDeprecationMessage(\Graphpinator\Typesystem\Exception\InterfaceCycle::MESSAGE);

        self::getInterfaceC()->getFields();
    }
}
