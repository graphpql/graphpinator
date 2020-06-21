<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Field;

final class FieldTest extends \PHPUnit\Framework\TestCase
{
    public function testFieldPrintSchema() : void
    {
        $fieldSchema = (new \Graphpinator\Field\Field('name', \Graphpinator\Type\Container\Container::String()->notNull()))->printSchema();

        self::assertSame('  name: String!', $fieldSchema);
    }
}