<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem;

use \Graphpinator\Typesystem\Container;
use \Graphpinator\Typesystem\Exception\InterfaceContractNewArgumentWithoutDefault;
use \Graphpinator\Typesystem\Field\ResolvableField;
use \Graphpinator\Typesystem\Field\ResolvableFieldSet;

final class InterfaceNewOptionalArgumentTest extends \PHPUnit\Framework\TestCase
{
    public static function createInterface() : \Graphpinator\Typesystem\InterfaceType
    {
        return new class extends \Graphpinator\Typesystem\InterfaceType {
            protected const NAME = 'SomeInterface';

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\FieldSet
            {
                return new \Graphpinator\Typesystem\Field\FieldSet([
                    new \Graphpinator\Typesystem\Field\Field(
                        'field',
                        Container::Int(),
                    ),
                ]);
            }
        };
    }

    public static function createChildType() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'ChildType';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Typesystem\InterfaceSet([InterfaceNewOptionalArgumentTest::createInterface()]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'field',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function ($parent, $argumentDefaultNull) : void {
                        },
                    )->setArguments(
                        new \Graphpinator\Typesystem\Argument\ArgumentSet([
                            \Graphpinator\Typesystem\Argument\Argument::create(
                                'argument',
                                \Graphpinator\Typesystem\Container::Int(),
                            ),
                        ]),
                    ),
                ]);
            }
        };
    }

    public function testAdditionalChildArgumentCannotBeNull() : void
    {
        $this->expectException(InterfaceContractNewArgumentWithoutDefault::class);

        self::createChildType()->getFields();
    }
}
