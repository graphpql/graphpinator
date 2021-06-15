<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem\Contract;

final class NamedDefinitionTest extends \PHPUnit\Framework\TestCase
{
    public function testModifiers() : void
    {
        $base = \Graphpinator\Typesystem\Container::String();

        self::assertSame('String', $base->getName());
        self::assertSame('String built-in type', $base->getDescription());

        self::assertSame($base, $base->notNull()->getInnerType());
        self::assertSame($base, $base->list()->getInnerType());
        self::assertSame($base, $base->notNullList()->getInnerType()->getInnerType()->getInnerType());
        self::assertSame($base, $base->notNull()->list()->notNull()->getInnerType()->getInnerType()->getInnerType());
        self::assertSame($base, $base->notNullList()->getNamedType());

        self::assertTrue($base->isInputable());
        self::assertTrue($base->notNull()->isInputable());
    }
}
