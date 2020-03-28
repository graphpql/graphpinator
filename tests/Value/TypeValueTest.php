<?php

declare(strict_types=1);

namespace Tests\Value;

final class TypeValueTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple(): void
    {
        $value = new \PGQL\Value\TypeValue(123, $this->createTestType());

        self::assertSame(123, $value->getRawValue());
    }

    public function testInvalid(): void
    {
        $this->expectException(\Exception::class);

        $value = new \PGQL\Value\TypeValue(456, $this->createTestType());
    }

    protected function createTestType() : \PGQL\Type\Type
    {
        return new class extends \PGQL\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(new \PGQL\Field\FieldSet([]));
            }

            protected function validateNonNullValue($rawValue): void
            {
                if ($rawValue === 123) {
                    return;
                }

                throw new \Exception();
            }
        };
    }
}
