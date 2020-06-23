<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Argument;

final class ArgumentTest extends \PHPUnit\Framework\TestCase
{
    public function testArgumentPrintSchema() : void
    {
        $argumentSchema = (new \Graphpinator\Argument\Argument('arg2', \Graphpinator\Tests\Spec\PrintSchema::getInput()))->printSchema();

        self::assertSame('  arg2: TestInput', $argumentSchema);
    }
}
