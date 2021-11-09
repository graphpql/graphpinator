<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Graphpinator\Typesystem\Exception\InterfaceOrTypeMustDefineOneOrMoreFields;
use \Graphpinator\Typesystem\Field\ResolvableFieldSet;

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

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([]);
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
