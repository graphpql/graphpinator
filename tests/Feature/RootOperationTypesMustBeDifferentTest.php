<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class RootOperationTypesMustBeDifferentTest extends \PHPUnit\Framework\TestCase
{
    public function testRootOperationTypesMustBeDifferent() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\RootOperationTypesMustBeDifferent::class);
        $this->expectExceptionMessage('The query, mutation, and subscription root types must all be different types if provided.');

        \Graphpinator\Tests\Spec\TestSchema::getFullSchema();
    }
}
