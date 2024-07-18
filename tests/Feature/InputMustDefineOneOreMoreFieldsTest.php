<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Exception\InputTypeMustDefineOneOreMoreFields;
use Graphpinator\Typesystem\InputType;
use PHPUnit\Framework\TestCase;

final class InputMustDefineOneOreMoreFieldsTest extends TestCase
{
    public static function getInputTypeMustDefineOneOreMoreFieldsInputType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'InvalidInputType';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([]);
            }
        };
    }

    public function testInputTypeMustDefineOneOreMoreFields() : void
    {
        $this->expectException(InputTypeMustDefineOneOreMoreFields::class);
        $this->expectExceptionMessage('An Input Object type must define one or more input fields.');

        self::getInputTypeMustDefineOneOreMoreFieldsInputType()->getArguments();
    }
}
