<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Value;

final class TypeValueTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple(): void
    {
        $value = new \Graphpinator\Value\TypeValue(123, $this->createTestType());
        $value2 = \Graphpinator\Value\TypeValue::create(123, $this->createTestType());

        self::assertSame(123, $value->getRawValue());
        self::assertSame(123, $value2->getRawValue());
    }

    public function testInvalid(): void
    {
        $this->expectException(\Exception::class);

        $value = new \Graphpinator\Value\TypeValue(456, $this->createTestType());
    }

    protected function createTestType() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            protected function validateNonNullValue($rawValue): void
            {
                if ($rawValue === 123) {
                    return;
                }

                throw new \Exception();
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([]);
            }
        };
    }
}
