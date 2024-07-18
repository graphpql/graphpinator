<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Typesystem\Exception\InterfaceOrTypeMustDefineOneOrMoreFields;
use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\InterfaceSet;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Value\TypeIntermediateValue;
use PHPUnit\Framework\TestCase;

final class InterfaceMustDefineOneOrMoreFieldsTest extends TestCase
{
    public static function getInterfaceMustDefineOneOrMoreFieldsType() : InterfaceType
    {
        return new class extends InterfaceType {
            protected const NAME = 'InvalidInterfaceType';

            public function __construct()
            {
                parent::__construct(
                    new InterfaceSet([]),
                );
            }

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([]);
            }
        };
    }

    public function testInterfaceMustDefineOneOrMoreFields() : void
    {
        $this->expectException(InterfaceOrTypeMustDefineOneOrMoreFields::class);
        $this->expectExceptionMessage('An Object type or interface must define one or more fields.');

        self::getInterfaceMustDefineOneOrMoreFieldsType()->getFields();
    }
}
