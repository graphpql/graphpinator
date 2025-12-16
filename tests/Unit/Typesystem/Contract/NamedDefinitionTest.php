<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem\Contract;

use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Visitor\GetNamedTypeVisitor;
use Graphpinator\Typesystem\Visitor\IsInputableVisitor;
use Graphpinator\Typesystem\Visitor\IsOutputableVisitor;
use PHPUnit\Framework\TestCase;

final class NamedDefinitionTest extends TestCase
{
    public function testModifiers() : void
    {
        $base = Container::String();

        self::assertSame('String', $base->getName());
        self::assertSame('String built-in type', $base->getDescription());

        self::assertSame($base, $base->notNull()->getInnerType());
        self::assertSame($base, $base->list()->getInnerType());
        self::assertSame($base, $base->notNullList()->getInnerType()->getInnerType()->getInnerType());
        self::assertSame($base, $base->notNull()->list()->notNull()->getInnerType()->getInnerType()->getInnerType());
        self::assertSame($base, $base->notNullList()->accept(new GetNamedTypeVisitor()));

        self::assertTrue($base->accept(new IsInputableVisitor()));
        self::assertTrue($base->accept(new IsOutputableVisitor()));
        self::assertTrue($base->notNull()->accept(new IsInputableVisitor()));
        self::assertTrue($base->notNull()->accept(new IsOutputableVisitor()));
    }
}
