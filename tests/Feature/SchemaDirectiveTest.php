<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

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

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet();
            }
        };
        $directive = new class extends \Graphpinator\Typesystem\Directive implements \Graphpinator\Typesystem\Location\SchemaLocation {
            protected const NAME = 'SomeSchemaDirective';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return  new \Graphpinator\Typesystem\Argument\ArgumentSet();
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
