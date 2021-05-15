<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class InterfaceContractMissingFieldTest extends \PHPUnit\Framework\TestCase
{
    public static function getTypeMissingField() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Type\InterfaceSet([
                        \Graphpinator\Tests\Unit\Type\InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'testField',
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

    public function testMissingField() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InterfaceContractMissingField::class);
        $this->expectExceptionMessage('Type "Abc" does not satisfy interface "Foo" - missing field "field".');

        self::getTypeMissingField()->getFields();
    }
}
