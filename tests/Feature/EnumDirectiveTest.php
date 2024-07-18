<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Directive;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\Location\EnumLocation;
use PHPUnit\Framework\TestCase;

final class EnumDirectiveTest extends TestCase
{
    public function testSimple() : void
    {
        $enum = new class extends EnumType {
            protected const NAME = 'SomeEnum';

            public function __construct()
            {
                parent::__construct(self::fromConstants());
            }
        };
        $directive = new class extends Directive implements EnumLocation {
            protected const NAME = 'SomeEnumDirective';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet();
            }
        };

        $enum->addDirective($directive);

        self::assertSame('SomeEnumDirective', $enum->getDirectiveUsages()->current()->getDirective()->getName());
    }
}
