<?php

declare(strict_types=1);

namespace Infinityloop\Tests\Graphpinator\Unit\Value;

final class InputValueTest extends \PHPUnit\Framework\TestCase
{
    public function testApplyDefaults() : void
    {
        $fields = [];
        $defaults = ['field' => \Infinityloop\Graphpinator\Value\ScalarValue::create('random', \Infinityloop\Graphpinator\Type\Scalar\ScalarType::String())];

        $type = $this->createMock(\Infinityloop\Graphpinator\Type\InputType::class);
        $type->expects($this->exactly(2))->method('getArguments')->willReturn(
            new \Infinityloop\Graphpinator\Argument\ArgumentSet([new \Infinityloop\Graphpinator\Argument\Argument('field', \Infinityloop\Graphpinator\Type\Scalar\ScalarType::String())])
        );
        $type->expects($this->once())->method('isInputable')->willReturn(true);
        $type->expects($this->once())->method('applyDefaults')->with($fields)->willReturn($defaults);

        $value = \Infinityloop\Graphpinator\Value\InputValue::create($fields, $type);
        self::assertTrue(isset($value['field']));
        self::assertFalse(isset($value['field0']));
        self::assertSame($defaults['field'], $value['field']);
    }

    public function testProvidedValue() : void
    {
        $fields = ['field' => 'random'];

        $type = $this->createMock(\Infinityloop\Graphpinator\Type\InputType::class);
        $type->expects($this->exactly(2))->method('getArguments')->willReturn(
            new \Infinityloop\Graphpinator\Argument\ArgumentSet([new \Infinityloop\Graphpinator\Argument\Argument('field', \Infinityloop\Graphpinator\Type\Scalar\ScalarType::String())])
        );
        $type->expects($this->once())->method('isInputable')->willReturn(true);
        $type->expects($this->once())->method('applyDefaults')->with($fields)->willReturn($fields);

        $value = \Infinityloop\Graphpinator\Value\InputValue::create($fields, $type);
        self::assertSame($fields, $value->getRawValue());
    }

    public function testNull() : void
    {
        $type = $this->createMock(\Infinityloop\Graphpinator\Type\InputType::class);

        self::assertInstanceOf(\Infinityloop\Graphpinator\Value\NullValue::class, \Infinityloop\Graphpinator\Value\InputValue::create(null, $type));
    }

    public function testInvalidField() : void
    {
        $this->expectException(\Exception::class);

        $fields = ['field0' => 123];

        $type = $this->createMock(\Infinityloop\Graphpinator\Type\InputType::class);
        $type->expects($this->exactly(2))->method('getArguments')->willReturn(new \Infinityloop\Graphpinator\Argument\ArgumentSet([]));
        $type->expects($this->once())->method('isInputable')->willReturn(true);
        $type->expects($this->once())->method('applyDefaults')->with($fields)->willReturn($fields);

        $value = \Infinityloop\Graphpinator\Value\InputValue::create($fields, $type);
    }
}
