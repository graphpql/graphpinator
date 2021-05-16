<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class CustomResolverValueTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple(): void
    {
        $value = new \Graphpinator\Value\ScalarValue(\Graphpinator\Container\Container::Int(), 123, true);
        self::assertSame(123, $value->getRawValue());
        self::assertSame(123, $value->getRawValue(true));
        $value->setResolverValue((object) ['modifiedInput' => 123]);
        self::assertSame(123, $value->getRawValue());
        self::assertInstanceOf(\stdClass::class, $value->getRawValue(true));
    }
}
