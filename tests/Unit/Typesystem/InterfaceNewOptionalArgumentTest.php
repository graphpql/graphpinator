<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Exception\InterfaceContractNewArgumentWithoutDefault;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\InterfaceSet;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Value\TypeIntermediateValue;
use PHPUnit\Framework\TestCase;

final class InterfaceNewOptionalArgumentTest extends TestCase
{
    public static function createInterface() : InterfaceType
    {
        return new class extends InterfaceType {
            protected const NAME = 'SomeInterface';

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field(
                        'field',
                        Container::Int(),
                    ),
                ]);
            }
        };
    }

    public static function createChildType() : Type
    {
        return new class extends Type {
            protected const NAME = 'ChildType';

            public function __construct()
            {
                parent::__construct(
                    new InterfaceSet([InterfaceNewOptionalArgumentTest::createInterface()]),
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
                        Container::Int(),
                        static function ($parent, $argumentDefaultNull) : void {
                        },
                    )->setArguments(
                        new ArgumentSet([
                            Argument::create(
                                'argument',
                                Container::Int(),
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
