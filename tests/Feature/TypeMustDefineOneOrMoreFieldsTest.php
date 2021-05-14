<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class TypeMustDefineOneOrMoreFieldsTest extends \PHPUnit\Framework\TestCase
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

    public function testTypeMustDefineOneOrMoreFields() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\TypeMustDefineOneOrMoreFields::class);
        $this->expectExceptionMessage('An Object type must define one or more fields.');

        self::getTypeMustDefineOneOrMoreFieldsType()->getFields();
    }
}
