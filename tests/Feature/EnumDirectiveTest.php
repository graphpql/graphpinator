<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Graphpinator\Typesystem\Location\EnumLocation;

final class EnumDirectiveTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $enum = new class extends \Graphpinator\Typesystem\EnumType {
            protected const NAME = 'SomeEnum';

            public function __construct()
            {
                parent::__construct(self::fromConstants());
            }
        };
        $directive = new class extends \Graphpinator\Typesystem\Directive implements EnumLocation {
            protected const NAME = 'SomeEnumDirective';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet();
            }
        };

        $enum->addDirective($directive);

        self::assertSame('SomeEnumDirective', $enum->getDirectiveUsages()->current()->getDirective()->getName());
    }
}
