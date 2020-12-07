<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Parser\FragmentSpread;

final class InlineFragmentSpreadTest extends \PHPUnit\Framework\TestCase
{
    public function testConstructor() : void
    {
        $val = new \Graphpinator\Parser\FragmentSpread\InlineFragmentSpread(
            new \Graphpinator\Parser\Field\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet()),
        );
        self::assertCount(0, $val->getFields());
        self::assertSame(\Graphpinator\Type\Introspection\DirectiveLocation::INLINE_FRAGMENT, $val->getDirectives()->getLocation());
        self::assertCount(0, $val->getDirectives());
    }
}
