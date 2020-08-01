<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Value;

final class InputValueTest extends \PHPUnit\Framework\TestCase
{
    public function testNull() : void
    {
        $type = $this->createMock(\Graphpinator\Type\InputType::class);

        self::assertInstanceOf(\Graphpinator\Resolver\Value\NullValue::class, \Graphpinator\Resolver\Value\InputValue::create(null, $type));
    }
}
