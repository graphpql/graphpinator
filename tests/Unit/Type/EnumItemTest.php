<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Type;

final class EnumItemTest extends \PHPUnit\Framework\TestCase
{
    public function testEnumItemPrintSchema() : void
    {
        $enumItemSchema = (new \Graphpinator\Type\Enum\EnumItem('enumItem'))->printSchema();

        self::assertSame('  enumItem', $enumItemSchema);
    }
}