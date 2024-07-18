<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Exception\InterfaceCycle;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Value\TypeIntermediateValue;
use PHPUnit\Framework\TestCase;

final class InterfaceCycleTest extends TestCase
{
    private static ?InterfaceType $interfaceA = null;
    private static ?InterfaceType $interfaceB = null;
    private static ?InterfaceType $interfaceC = null;

    public static function getInterfaceB() : InterfaceType
    {
        if (self::$interfaceB instanceof InterfaceType) {
            return self::$interfaceB;
        }

        self::$interfaceB = new class extends InterfaceType {
            protected const NAME = 'BInterface';

            public function __construct()
            {
                parent::__construct();
            }

            public function initImplements() : void
            {
                $this->implements[] = InterfaceCycleTest::getInterfaceA();
            }

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    Field::create(
                        'fieldInt',
                        Container::Int(),
                    ),
                ]);
            }
        };

        self::$interfaceB->initImplements();

        return self::$interfaceB;
    }

    public static function getInterfaceC() : InterfaceType
    {
        if (self::$interfaceC instanceof InterfaceType) {
            return self::$interfaceC;
        }

        self::$interfaceC = new class extends InterfaceType {
            protected const NAME = 'CInterface';

            public function __construct()
            {
                parent::__construct();
            }

            public function initImplements() : void
            {
                $this->implements[] = InterfaceCycleTest::getInterfaceB();
            }

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    Field::create(
                        'fieldInt',
                        Container::Int(),
                    ),
                ]);
            }
        };

        self::$interfaceC->initImplements();

        return self::$interfaceC;
    }

    public static function getInterfaceA() : InterfaceType
    {
        if (self::$interfaceA instanceof InterfaceType) {
            return self::$interfaceA;
        }

        self::$interfaceA = new class extends InterfaceType {
            protected const NAME = 'InterfaceA';

            public function __construct()
            {
                parent::__construct();
            }

            public function initImplements() : void
            {
                $this->implements[] = InterfaceCycleTest::getInterfaceC();
            }

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    Field::create(
                        'fieldInt',
                        Container::Int(),
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

        $this->expectException(InterfaceCycle::class);
        $this->expectExceptionMessage('Interface implement cycle detected (BInterface -> InterfaceA -> CInterface).');

        self::getInterfaceA()->getFields();
    }

    public function testInvalidB() : void
    {
        self::$interfaceA = null;
        self::$interfaceB = null;
        self::$interfaceC = null;

        $this->expectException(InterfaceCycle::class);
        $this->expectExceptionMessage('Interface implement cycle detected (CInterface -> BInterface -> InterfaceA).');

        self::getInterfaceB()->getFields();
    }

    public function testInvalidC() : void
    {
        self::$interfaceA = null;
        self::$interfaceB = null;
        self::$interfaceC = null;

        $this->expectException(InterfaceCycle::class);
        $this->expectExceptionMessage('Interface implement cycle detected (InterfaceA -> CInterface -> BInterface).');

        self::getInterfaceC()->getFields();
    }
}
