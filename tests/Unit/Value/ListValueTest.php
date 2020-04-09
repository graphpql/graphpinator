<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Value;

final class ListValueTest extends \PHPUnit\Framework\TestCase
{
    public function testApplyDefaults() : void
    {
        $type = $this->createTestInput()->list();
        $value = \Graphpinator\Value\ListValue::create([[], []], $type);
        self::assertCount(2, $value);
        self::assertCount(2, $value->getRawValue());

        foreach ($value as $key => $listValue) {
            self::assertIsInt($key);
            self::assertInstanceOf(\Graphpinator\Value\InputValue::class, $listValue);
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
        $value = \Graphpinator\Value\ListValue::create(null, $type);
        self::assertInstanceOf(\Graphpinator\Value\NullValue::class, $value);
    }

    public function testInvalid() : void
    {
        $this->expectException(\Exception::class);

        $value = new \Graphpinator\Value\ListValue(123, new \Graphpinator\Type\ListType($this->createTestInput()));
    }

    protected function createTestInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Argument\ArgumentSet([new \Graphpinator\Argument\Argument(
                        'field', \Graphpinator\Type\Container\Container::String(), 'random',
                    )]),
                );
            }
        };
    }
}
