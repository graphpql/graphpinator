<?php

declare(strict_types=1);

namespace Tests\Value;

final class ListValueTest extends \PHPUnit\Framework\TestCase
{
    public function testApplyDefaults() : void
    {
        $value = \PGQL\Value\ListValue::create([[], []], new \PGQL\Type\ListType($this->createTestInput()));
        self::assertCount(2, $value);
        self::assertCount(2, $value->getRawValue());

        foreach ($value as $key => $listValue) {
            self::assertIsInt($key);
            self::assertInstanceOf(\PGQL\Value\InputValue::class, $listValue);
            self::assertSame('random', $listValue['field']->getRawValue());
        }

        foreach ($value->getRawValue() as $key => $listValue) {
            self::assertIsInt($key);
            self::assertIsArray($listValue);
            self::assertSame('random', $listValue['field']);
        }
    }

    public function testInvalid() : void
    {
        $this->expectException(\Exception::class);

        $value = new \PGQL\Value\ListValue(123, new \PGQL\Type\ListType($this->createTestInput()));
    }

    protected function createTestInput() : \PGQL\Type\InputType
    {
        return new class extends \PGQL\Type\InputType {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \PGQL\Argument\ArgumentSet([new \PGQL\Argument\Argument(
                        'field', \PGQL\Type\Scalar\ScalarType::String(), 'random',
                    )]),
                );
            }
        };
    }
}
