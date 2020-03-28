<?php

declare(strict_types=1);

namespace Tests\Value;

final class InputValueTest extends \PHPUnit\Framework\TestCase
{
    public function testApplyDefaults() : void
    {
        $fields = [];
        $defaults = ['field' => \PGQL\Value\ScalarValue::create('random', \PGQL\Type\Scalar\ScalarType::String())];

        $type = $this->createMock(\PGQL\Type\InputType::class);
        $type->expects($this->exactly(2))->method('getArguments')->willReturn(
            new \PGQL\Argument\ArgumentSet([new \PGQL\Argument\Argument('field', \PGQL\Type\Scalar\ScalarType::String())])
        );
        $type->expects($this->once())->method('applyDefaults')->with($fields)->willReturn($defaults);

        $value = \PGQL\Value\InputValue::create($fields, $type);
        self::assertTrue(isset($value['field']));
        self::assertFalse(isset($value['field0']));
        self::assertSame($defaults['field'], $value['field']);
    }

    public function testProvidedValue() : void
    {
        $fields = ['field' => 'random'];

        $type = $this->createMock(\PGQL\Type\InputType::class);
        $type->expects($this->exactly(2))->method('getArguments')->willReturn(
            new \PGQL\Argument\ArgumentSet([new \PGQL\Argument\Argument('field', \PGQL\Type\Scalar\ScalarType::String())])
        );
        $type->expects($this->once())->method('applyDefaults')->with($fields)->willReturn($fields);

        $value = \PGQL\Value\InputValue::create($fields, $type);
        self::assertSame($fields, $value->getRawValue());
    }

    public function testNull() : void
    {
        $type = $this->createMock(\PGQL\Type\InputType::class);

        self::assertInstanceOf(\PGQL\Value\NullValue::class, \PGQL\Value\InputValue::create(null, $type));
    }

    public function testInvalidField() : void
    {
        $this->expectException(\Exception::class);

        $fields = ['field0' => 123];

        $type = $this->createMock(\PGQL\Type\InputType::class);
        $type->expects($this->exactly(2))->method('getArguments')->willReturn(new \PGQL\Argument\ArgumentSet([]));
        $type->expects($this->once())->method('applyDefaults')->with($fields)->willReturn($fields);

        $value = \PGQL\Value\InputValue::create($fields, $type);
    }
}
