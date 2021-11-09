<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Graphpinator\Typesystem\Field\ResolvableFieldSet;
use \Graphpinator\Typesystem\Location\SchemaLocation;

final class SchemaDirectiveTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $query = new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'Query';

            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet();
            }
        };
        $directive = new class extends \Graphpinator\Typesystem\Directive implements SchemaLocation {
            protected const NAME = 'SomeSchemaDirective';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet();
            }
        };

        $schema = new \Graphpinator\Typesystem\Schema(
            new \Graphpinator\SimpleContainer([$query], []),
            $query,
        );
        $schema->addDirective($directive);

        self::assertSame('SomeSchemaDirective', $schema->getDirectiveUsages()->current()->getDirective()->getName());
    }
}
