<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class InputTypeMustDefineOneOreMoreFieldsTest extends \PHPUnit\Framework\TestCase
{
    public static function getInputTypeMustDefineOneOreMoreFieldsInputType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'InvalidInputType';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([]);
            }
        };
    }

    public function testInputTypeMustDefineOneOreMoreFields() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InputTypeMustDefineOneOreMoreFields::class);
        $this->expectExceptionMessage('An Input Object type must define one or more input fields.');

        self::getInputTypeMustDefineOneOreMoreFieldsInputType()->getArguments();
    }
}