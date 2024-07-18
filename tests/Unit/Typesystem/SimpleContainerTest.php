<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem;

use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Contract\NamedType;
use Graphpinator\Typesystem\Directive;
use PHPUnit\Framework\TestCase;

final class SimpleContainerTest extends TestCase
{
    public function testSimple() : void
    {
        $container = new SimpleContainer([], []);

        self::assertCount(0, $container->getTypes());
        self::assertCount(0, $container->getDirectives());

        foreach ([
            'ID',
            'Int',
            'Float',
            'String',
            'Boolean',
            '__Schema',
            '__Type',
            '__TypeKind',
            '__Field',
            '__EnumValue',
            '__InputValue',
             '__Directive',
            '__DirectiveLocation',
                 ] as $typeName) {
            self::assertInstanceOf(NamedType::class, $container->getType($typeName));
        }

        foreach (['skip', 'include'] as $directiveName) {
            self::assertInstanceOf(Directive::class, $container->getDirective($directiveName));
        }
    }
}
