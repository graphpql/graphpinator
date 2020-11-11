<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Parser\Value;

final class VariableRefTest extends \PHPUnit\Framework\TestCase
{
    public function testGetRawValue() : void
    {
        $this->expectException(\Graphpinator\Exception\OperationNotSupported::class);

        $val = new \Graphpinator\Parser\Value\VariableRef('varName');
        $val->getRawValue();
    }
}
