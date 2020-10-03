<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Value;

final class ListValueTest extends \PHPUnit\Framework\TestCase
{
    public function testApplyDefaults() : void
    {
        $type = $this->createTestInput()->list();
        $value = \Graphpinator\Resolver\Value\ListValue::create([new \stdClass(), new \stdClass()], $type);
        self::assertCount(2, $value);
        self::assertCount(2, $value->getRawValue());

        foreach ($value as $key => $listValue) {
            self::assertIsInt($key);
            self::assertInstanceOf(\Graphpinator\Resolver\Value\InputValue::class, $listValue);
            self::assertSame('random', $listValue['field']->getRawValue());
        }

        foreach ($value->getRawValue() as $key => $listValue) {
            self::assertIsInt($key);
            self::assertInstanceOf(\stdClass::class, $listValue);
            self::assertSame('random', $listValue->field);
        }
    }

    public function testApplyDefaultsNull() : void
    {
        $type = $this->createTestInput()->list();
        $value = \Graphpinator\Resolver\Value\ListValue::create(null, $type);
        self::assertInstanceOf(\Graphpinator\Resolver\Value\NullValue::class, $value);
    }

    public function testInvalid() : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        new \Graphpinator\Resolver\Value\ListValue(123, new \Graphpinator\Type\ListType($this->createTestInput()));
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
                        \Graphpinator\Container\Container::String(),
                        'random',
                    ),
                ]);
            }
        };
    }
}
