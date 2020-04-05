<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Type\Scalar;

final class ScalarTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate(): void
    {
        $int = \Graphpinator\Type\Scalar\ScalarType::Int();
        $float = \Graphpinator\Type\Scalar\ScalarType::Float();
        $string = \Graphpinator\Type\Scalar\ScalarType::String();
        $boolean = \Graphpinator\Type\Scalar\ScalarType::Boolean();
        $id = \Graphpinator\Type\Scalar\ScalarType::ID();

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
        $result = \Graphpinator\Field\ResolveResult::fromValidated($value);

        self::assertSame($value, $type->resolveFields(null, $result));
    }
}
