<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem;

final class SimpleContainerTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $container = new \Graphpinator\SimpleContainer([], []);

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
            self::assertInstanceOf(\Graphpinator\Typesystem\Contract\NamedType::class, $container->getType($typeName));
        }

        foreach (['skip', 'include'] as $directiveName) {
            self::assertInstanceOf(\Graphpinator\Typesystem\Directive::class, $container->getDirective($directiveName));
        }
    }
}
