<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class InterfaceCycleTest extends \PHPUnit\Framework\TestCase
{
    public function testInvalid() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InterfaceCycle::class);
        $this->expectDeprecationMessage(\Graphpinator\Exception\Type\InterfaceCycle::MESSAGE);

        self::getChildInterface()->getFields();
    }

    public static function getGrandParentInterface() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'GrandParentInterfaceType';

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
    }

    public static function getParentInterface() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'ParentInterfaceType';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Type\InterfaceSet([
                        InterfaceCycleTest::getGrandParentInterface(),
                    ]),
                );
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
                    \Graphpinator\Field\Field::create(
                        'fieldString',
                        \Graphpinator\Container\Container::String(),
                    ),
                ]);
            }
        };
    }

    public static function getChildInterface() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'ChildInterfaceType';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Type\InterfaceSet([
                        InterfaceCycleTest::getGrandParentInterface(),
                        InterfaceCycleTest::getParentInterface(),
                    ]),
                );
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
                    \Graphpinator\Field\Field::create(
                        'fieldString',
                        \Graphpinator\Container\Container::String(),
                    ),
                ]);
            }
        };
    }
}
