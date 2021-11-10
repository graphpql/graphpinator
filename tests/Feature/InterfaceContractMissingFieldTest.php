<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Graphpinator\Typesystem\Exception\InterfaceContractMissingField;

final class InterfaceContractMissingFieldTest extends \PHPUnit\Framework\TestCase
{
    public static function createMainInterface() : \Graphpinator\Typesystem\InterfaceType
    {
        return new class extends \Graphpinator\Typesystem\InterfaceType {
            protected const NAME = 'Bar';

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\FieldSet
            {
                return new \Graphpinator\Typesystem\Field\FieldSet([
                    new \Graphpinator\Typesystem\Field\Field(
                        'field',
                        \Graphpinator\Typesystem\Container::Int(),
                    ),
                ]);
            }
        };
    }

    public static function getTypeMissingField() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Typesystem\InterfaceSet([
                        InterfaceContractMissingFieldTest::createMainInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'differentField',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function () : void {
                        },
                    ),
                ]);
            }
        };
    }

    public function testTypeMissingField() : void
    {
        $this->expectException(InterfaceContractMissingField::class);
        $this->expectExceptionMessage('Type "Abc" does not satisfy interface "Bar" - missing field "field".');

        self::getTypeMissingField()->getFields();
    }
}
