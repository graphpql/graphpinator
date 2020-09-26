<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Value;

final class InputValueTest extends \PHPUnit\Framework\TestCase
{
    public function testImmutabilitySet() : void
    {
        $this->expectException(\Graphpinator\Exception\OperationNotSupported::class);
        $this->expectDeprecationMessage(\Graphpinator\Exception\OperationNotSupported::MESSAGE);

        $type = $this->createTestInput();
        $value = \Graphpinator\Resolver\Value\InputValue::create((object) [], $type);

        $value['field'] = 'text';
    }

    public function testImmutabilityUnset() : void
    {
        $this->expectException(\Graphpinator\Exception\OperationNotSupported::class);
        $this->expectDeprecationMessage(\Graphpinator\Exception\OperationNotSupported::MESSAGE);

        $type = $this->createTestInput();
        $value = \Graphpinator\Resolver\Value\InputValue::create((object) ['field' => 'text'], $type);

        unset($value['field']);
    }

    protected function createTestInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'Abc';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'field',
                        \Graphpinator\Type\Container\Container::String(),
                        'random',
                    ),
                ]);
            }
        };
    }
}
