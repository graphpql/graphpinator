<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class InterfaceOrTypeMustDefineOneOrMoreFieldsTest extends \PHPUnit\Framework\TestCase
{
    public static function getTypeMustDefineOneOrMoreFieldsType() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'InvalidType';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getInterfaceMustDefineOneOrMoreFieldsType() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'InvalidInterfaceType';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Type\InterfaceSet([]),
                );
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([]);
            }
        };
    }

    public function testTypeMustDefineOneOrMoreFields() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InterfaceOrTypeMustDefineOneOrMoreFields::class);
        $this->expectExceptionMessage('An Object type or interface must define one or more fields.');

        self::getTypeMustDefineOneOrMoreFieldsType()->getFields();
    }

    public function testInterfaceMustDefineOneOrMoreFields() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InterfaceOrTypeMustDefineOneOrMoreFields::class);
        $this->expectExceptionMessage('An Object type or interface must define one or more fields.');

        self::getInterfaceMustDefineOneOrMoreFieldsType()->getFields();
    }
}
