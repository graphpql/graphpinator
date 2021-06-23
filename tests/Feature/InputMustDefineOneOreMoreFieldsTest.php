<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class InputMustDefineOneOreMoreFieldsTest extends \PHPUnit\Framework\TestCase
{
    public static function getInputTypeMustDefineOneOreMoreFieldsInputType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'InvalidInputType';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([]);
            }
        };
    }

    public function testInputTypeMustDefineOneOreMoreFields() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\InputTypeMustDefineOneOreMoreFields::class);
        $this->expectExceptionMessage('An Input Object type must define one or more input fields.');

        self::getInputTypeMustDefineOneOreMoreFieldsInputType()->getArguments();
    }
}
