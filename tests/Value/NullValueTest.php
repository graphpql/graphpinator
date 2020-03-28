<?php

declare(strict_types=1);

namespace Tests\Value;

final class NullValueTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple(): void
    {
        $value = new \PGQL\Value\NullValue(\PGQL\Type\Scalar\ScalarType::Int());

        self::assertNull($value->getRawValue());
    }

    public function testInvalid(): void
    {
        $this->expectException(\Exception::class);

        \PGQL\Value\ScalarValue::create(null, \PGQL\Type\Scalar\ScalarType::Int()->notNull());
    }
}
