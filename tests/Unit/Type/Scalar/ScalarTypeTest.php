<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Type\Scalar;

final class ScalarTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate(): void
    {
        $int = \Graphpinator\Type\Container\Container::Int();
        $float = \Graphpinator\Type\Container\Container::Float();
        $string = \Graphpinator\Type\Container\Container::String();
        $boolean = \Graphpinator\Type\Container\Container::Boolean();
        $id = \Graphpinator\Type\Container\Container::ID();

        self::assertInstanceOf(\Graphpinator\Type\Scalar\ScalarType::class, $int);
        self::assertInstanceOf(\Graphpinator\Type\Scalar\ScalarType::class, $float);
        self::assertInstanceOf(\Graphpinator\Type\Scalar\ScalarType::class, $string);
        self::assertInstanceOf(\Graphpinator\Type\Scalar\ScalarType::class, $boolean);
        self::assertInstanceOf(\Graphpinator\Type\Scalar\ScalarType::class, $id);
    }

    public function testResolveFields(): void
    {
        $type = new \Graphpinator\Type\Scalar\BooleanType();
        $value = new \Graphpinator\Value\ScalarValue(true, $type);
        $result = \Graphpinator\Resolver\FieldResult::fromValidated($value);

        self::assertSame($value, $type->resolve(null, $result));
    }
}
