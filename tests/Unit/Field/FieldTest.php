<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Field;

final class FieldTest extends \PHPUnit\Framework\TestCase
{
    public function testFieldPrintSchema() : void
    {
        $fieldSchema = (new \Graphpinator\Field\Field('name', \Graphpinator\Container\Container::String()->notNull()))->printSchema(0);

        self::assertSame('name: String!', $fieldSchema);
    }
}
