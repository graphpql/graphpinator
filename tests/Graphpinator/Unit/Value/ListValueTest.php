<?php

declare(strict_types=1);

namespace Infinityloop\Tests\Graphpinator\Unit\Value;

final class ListValueTest extends \PHPUnit\Framework\TestCase
{
    public function testApplyDefaults() : void
    {
        $type = $this->createTestInput()->list();
        $value = \Infinityloop\Graphpinator\Value\ListValue::create([[], []], $type);
        self::assertCount(2, $value);
        self::assertCount(2, $value->getRawValue());

        foreach ($value as $key => $listValue) {
            self::assertIsInt($key);
            self::assertInstanceOf(\Infinityloop\Graphpinator\Value\InputValue::class, $listValue);
            self::assertSame('random', $listValue['field']->getRawValue());
        }

        foreach ($value->getRawValue() as $key => $listValue) {
            self::assertIsInt($key);
            self::assertIsArray($listValue);
            self::assertSame('random', $listValue['field']);
        }
    }

    public function testApplyDefaultsNull() : void
    {
        $type = $this->createTestInput()->list();
        $value = \Infinityloop\Graphpinator\Value\ListValue::create(null, $type);
        self::assertInstanceOf(\Infinityloop\Graphpinator\Value\NullValue::class, $value);
    }

    public function testInvalid() : void
    {
        $this->expectException(\Exception::class);

        $value = new \Infinityloop\Graphpinator\Value\ListValue(123, new \Infinityloop\Graphpinator\Type\ListType($this->createTestInput()));
    }

    protected function createTestInput() : \Infinityloop\Graphpinator\Type\InputType
    {
        return new class extends \Infinityloop\Graphpinator\Type\InputType {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Infinityloop\Graphpinator\Argument\ArgumentSet([new \Infinityloop\Graphpinator\Argument\Argument(
                        'field', \Infinityloop\Graphpinator\Type\Scalar\ScalarType::String(), 'random',
                    )]),
                );
            }
        };
    }
}
