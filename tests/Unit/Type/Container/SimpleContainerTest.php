<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Container;

final class SimpleContainerTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $container = new \Graphpinator\Type\Container\SimpleContainer([], []);

        self::assertCount(1, $container->getTypes());
        self::assertCount(6, $container->getDirectives());

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
            self::assertInstanceOf(\Graphpinator\Type\Contract\NamedDefinition::class, $container->getType($typeName));
        }

        foreach (['skip', 'include'] as $directiveName) {
            self::assertInstanceOf(\Graphpinator\Directive\Directive::class, $container->getDirective($directiveName));
        }
    }
}
