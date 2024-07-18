<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Exception\InterfaceContractMissingField;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\InterfaceSet;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Value\TypeIntermediateValue;
use PHPUnit\Framework\TestCase;

final class InterfaceContractMissingFieldTest extends TestCase
{
    public static function createMainInterface() : InterfaceType
    {
        return new class extends InterfaceType {
            protected const NAME = 'Bar';

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

    public static function getTypeMissingField() : Type
    {
        return new class extends Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new InterfaceSet([
                        InterfaceContractMissingFieldTest::createMainInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'differentField',
                        Container::Int(),
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
