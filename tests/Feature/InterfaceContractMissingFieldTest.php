<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class InterfaceContractMissingFieldTest extends \PHPUnit\Framework\TestCase
{
    public static function createMainInterface() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'Bar';

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

    public static function getTypeMissingField() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Type\InterfaceSet([
                        InterfaceContractMissingFieldTest::createMainInterface(),
                    ]),
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'differentField',
                        \Graphpinator\Container\Container::Int(),
                        static function () : void {
                        },
                    ),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public function testTypeMissingField() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InterfaceContractMissingField::class);
        $this->expectExceptionMessage('Type "Abc" does not satisfy interface "Bar" - missing field "field".');

        self::getTypeMissingField()->getFields();
    }
}
