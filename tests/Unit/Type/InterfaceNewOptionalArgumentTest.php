<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type;

final class InterfaceNewOptionalArgumentTest extends \PHPUnit\Framework\TestCase
{
    public static function getTypeAdditionalChildArgumentCannotBeNull() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Fff';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Container\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    \Graphpinator\Field\ResolvableField::create(
                        'argument',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'argumentName',
                            \Graphpinator\Container\Container::Int(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'argumentNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'argumentName',
                            \Graphpinator\Container\Container::Int()->notNull(),
                        ),
                        \Graphpinator\Argument\Argument::create(
                            'argumentDefaultNull',
                            \Graphpinator\Container\Container::Int(),
                        ),
                    ])),
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
                'Foo',
                'argumentDefaultNull',
                'argumentNotNull',
            ))->getMessage(),
        );

        self::getTypeAdditionalChildArgumentCannotBeNull()->getFields();
    }
}
