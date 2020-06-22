<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Value;

final class NullValueTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $value = new \Graphpinator\Resolver\Value\NullValue(\Graphpinator\Type\Container\Container::Int());

        self::assertNull($value->getRawValue());
    }

    public function testInvalid() : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        \Graphpinator\Resolver\Value\LeafValue::create(null, \Graphpinator\Type\Container\Container::Int()->notNull());
    }
}
