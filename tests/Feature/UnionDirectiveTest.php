<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class UnionDirectiveTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $union = new class extends \Graphpinator\Typesystem\UnionType {
            protected const NAME = 'SomeUnion';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Typesystem\TypeSet());
            }

            public function createResolvedValue(mixed $rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
            }
        };
        $directive = new class extends \Graphpinator\Typesystem\Directive implements \Graphpinator\Typesystem\Location\UnionLocation {
            protected const NAME = 'SomeUnionDirective';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return  new \Graphpinator\Typesystem\Argument\ArgumentSet();
            }
        };

        $union->addDirective($directive);

        self::assertSame('SomeUnionDirective', $union->getDirectiveUsages()->current()->getDirective()->getName());
    }
}
