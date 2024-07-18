<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Typesystem\Exception\InterfaceOrTypeMustDefineOneOrMoreFields;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Type;
use PHPUnit\Framework\TestCase;

final class TypeMustDefineOneOrMoreFieldsTest extends TestCase
{
    public static function getTypeMustDefineOneOrMoreFieldsType() : Type
    {
        return new class extends Type {
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
