<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Directive;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Location\SchemaLocation;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use PHPUnit\Framework\TestCase;

final class SchemaDirectiveTest extends TestCase
{
    public function testSimple() : void
    {
        $query = new class extends Type {
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
        $directive = new class extends Directive implements SchemaLocation {
            protected const NAME = 'SomeSchemaDirective';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet();
            }
        };

        $schema = new Schema(
            new SimpleContainer([$query], []),
            $query,
        );
        $schema->addDirective($directive);

        self::assertSame('SomeSchemaDirective', $schema->getDirectiveUsages()->current()->getDirective()->getName());
    }
}
