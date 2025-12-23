<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Typesystem\Container;
use Graphpinator\Value\ScalarValue;
use Graphpinator\Value\Visitor\GetResolverValueVisitor;
use PHPUnit\Framework\TestCase;

final class CustomResolverValueTest extends TestCase
{
    public function testSimple() : void
    {
        $value = new ScalarValue(Container::Int(), 123, true);
        self::assertSame(123, $value->getRawValue());
        self::assertSame(123, $value->accept(new GetResolverValueVisitor()));
        $value->setResolverValue((object) ['modifiedInput' => 123]);
        self::assertSame(123, $value->getRawValue());
        self::assertInstanceOf(\stdClass::class, $value->accept(new GetResolverValueVisitor()));
    }
}
