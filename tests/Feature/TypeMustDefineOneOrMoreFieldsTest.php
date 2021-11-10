<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Graphpinator\Typesystem\Exception\InterfaceOrTypeMustDefineOneOrMoreFields;

final class TypeMustDefineOneOrMoreFieldsTest extends \PHPUnit\Framework\TestCase
{
    public static function getTypeMustDefineOneOrMoreFieldsType() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'InvalidType';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([]);
            }
        };
    }

    public function testTypeMustDefineOneOrMoreFields() : void
    {
        $this->expectException(InterfaceOrTypeMustDefineOneOrMoreFields::class);
        $this->expectExceptionMessage('An Object type or interface must define one or more fields.');

        self::getTypeMustDefineOneOrMoreFieldsType()->getFields();
    }
}
