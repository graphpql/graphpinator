<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Value;

final class NullValueTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple(): void
    {
        $value = new \Graphpinator\Value\NullValue(\Graphpinator\Type\Scalar\ScalarType::Int());

        self::assertNull($value->getRawValue());
    }

    public function testInvalid(): void
    {
        $this->expectException(\Exception::class);

        \Graphpinator\Value\ScalarValue::create(null, \Graphpinator\Type\Scalar\ScalarType::Int()->notNull());
    }
}
