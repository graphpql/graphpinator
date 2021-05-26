<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type;

final class InterfaceNewOptionalArgumentTest extends \PHPUnit\Framework\TestCase
{
    public static function createInterface() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'SomeInterface';

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
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

    public static function createChildType() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'ChildType';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Type\InterfaceSet([InterfaceNewOptionalArgumentTest::createInterface()]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
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
                                'argument',
                                \Graphpinator\Container\Container::Int(),
                            ),
                        ]),
                    ),
                ]);
            }
        };
    }

    public function testAdditionalChildArgumentCannotBeNull() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InterfaceContractNewArgumentWithoutDefault::class);
        $this->expectExceptionMessage(
            (new \Graphpinator\Exception\Type\InterfaceContractNewArgumentWithoutDefault(
                'ChildType',
                'SomeInterface',
                'field',
                'argument',
            ))->getMessage(),
        );

        self::createChildType()->getFields();
    }
}
