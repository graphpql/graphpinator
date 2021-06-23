<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class InterfaceMustDefineOneOrMoreFieldsTest extends \PHPUnit\Framework\TestCase
{
    public static function getInterfaceMustDefineOneOrMoreFieldsType() : \Graphpinator\Typesystem\InterfaceType
    {
        return new class extends \Graphpinator\Typesystem\InterfaceType {
            protected const NAME = 'InvalidInterfaceType';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Typesystem\InterfaceSet([]),
                );
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\FieldSet
            {
                return new \Graphpinator\Typesystem\Field\FieldSet([]);
            }
        };
    }

    public function testInterfaceMustDefineOneOrMoreFields() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\InterfaceOrTypeMustDefineOneOrMoreFields::class);
        $this->expectExceptionMessage('An Object type or interface must define one or more fields.');

        self::getInterfaceMustDefineOneOrMoreFieldsType()->getFields();
    }
}
