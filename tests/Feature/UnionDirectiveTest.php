<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Directive;
use Graphpinator\Typesystem\Location\UnionLocation;
use Graphpinator\Typesystem\TypeSet;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Value\TypeIntermediateValue;
use PHPUnit\Framework\TestCase;

final class UnionDirectiveTest extends TestCase
{
    public function testSimple() : void
    {
        $union = new class extends UnionType {
            protected const NAME = 'SomeUnion';

            public function __construct()
            {
                parent::__construct(new TypeSet());
            }

            public function createResolvedValue(mixed $rawValue) : TypeIntermediateValue
            {
            }
        };
        $directive = new class extends Directive implements UnionLocation {
            protected const NAME = 'SomeUnionDirective';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet();
            }
        };

        $union->addDirective($directive);

        self::assertSame('SomeUnionDirective', $union->getDirectiveUsages()->current()->getDirective()->getName());
    }
}
