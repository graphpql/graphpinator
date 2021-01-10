<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type;

final class InterfaceNewOptionalArgumentTest extends \PHPUnit\Framework\TestCase
{
    public static function createDefaultInterface() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'DefaultInterface';

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(
                    InterfaceNewOptionalArgumentTest::getInterfaceContractNewArgumentWithoutDefault(),
                    123,
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field(
                        'field',
                        \Graphpinator\Container\Container::Int(),
                    ),
                ]);
            }
        };
    }

    public static function getInterfaceContractNewArgumentWithoutDefault() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Fff';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceNewOptionalArgumentTest::createDefaultInterface(),
                    ]),
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    \Graphpinator\Field\ResolvableField::create(
                        'field',
                        \Graphpinator\Container\Container::Int(),
                        static function ($parent, $argumentDefaultNull) : void {
                        },
                    )->setArguments(
                        new \Graphpinator\Argument\ArgumentSet([
                            \Graphpinator\Argument\Argument::create(
                                'argumentDefaultNull',
                                \Graphpinator\Container\Container::Int(),
                            ),
                        ]),
                    ),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public function testAdditionalChildArgumentCannotBeNull() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InterfaceContractNewArgumentWithoutDefault::class);
        $this->expectExceptionMessage(
            (new \Graphpinator\Exception\Type\InterfaceContractNewArgumentWithoutDefault(
                'Fff',
                'DefaultInterface',
                'field',
                'argumentDefaultNull',
            ))->getMessage(),
        );

        self::getInterfaceContractNewArgumentWithoutDefault()->getFields();
    }
}
