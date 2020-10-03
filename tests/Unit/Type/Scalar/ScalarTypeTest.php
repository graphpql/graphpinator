<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Scalar;

final class ScalarTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate() : void
    {
        $int = \Graphpinator\Container\Container::Int();
        $float = \Graphpinator\Container\Container::Float();
        $string = \Graphpinator\Container\Container::String();
        $boolean = \Graphpinator\Container\Container::Boolean();
        $id = \Graphpinator\Container\Container::ID();

        self::assertInstanceOf(\Graphpinator\Type\Scalar\ScalarType::class, $int);
        self::assertInstanceOf(\Graphpinator\Type\Scalar\ScalarType::class, $float);
        self::assertInstanceOf(\Graphpinator\Type\Scalar\ScalarType::class, $string);
        self::assertInstanceOf(\Graphpinator\Type\Scalar\ScalarType::class, $boolean);
        self::assertInstanceOf(\Graphpinator\Type\Scalar\ScalarType::class, $id);
    }

    public function testResolveFields() : void
    {
        $type = new \Graphpinator\Type\Scalar\BooleanType();
        $value = new \Graphpinator\Resolver\Value\LeafValue(true, $type);
        $result = \Graphpinator\Resolver\FieldResult::fromValidated($value);

        self::assertSame($value, $type->resolve(null, $result));
    }
}
