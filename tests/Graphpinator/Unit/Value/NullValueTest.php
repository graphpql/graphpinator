<?php

declare(strict_types=1);

namespace Infinityloop\Tests\Graphpinator\Unit\Value;

final class NullValueTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple(): void
    {
        $value = new \Infinityloop\Graphpinator\Value\NullValue(\Infinityloop\Graphpinator\Type\Scalar\ScalarType::Int());

        self::assertNull($value->getRawValue());
    }

    public function testInvalid(): void
    {
        $this->expectException(\Exception::class);

        \Infinityloop\Graphpinator\Value\ScalarValue::create(null, \Infinityloop\Graphpinator\Type\Scalar\ScalarType::Int()->notNull());
    }
}
