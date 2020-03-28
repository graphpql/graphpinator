<?php

declare(strict_types=1);

namespace Infinityloop\Tests\Graphpinator\Unit\Type\Scalar;

final class ScalarTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate(): void
    {
        $int = \Infinityloop\Graphpinator\Type\Scalar\ScalarType::Int();
        $float = \Infinityloop\Graphpinator\Type\Scalar\ScalarType::Float();
        $string = \Infinityloop\Graphpinator\Type\Scalar\ScalarType::String();
        $boolean = \Infinityloop\Graphpinator\Type\Scalar\ScalarType::Boolean();
        $id = \Infinityloop\Graphpinator\Type\Scalar\ScalarType::ID();

        self::assertInstanceOf(\Infinityloop\Graphpinator\Type\Scalar\ScalarType::class, $int);
        self::assertInstanceOf(\Infinityloop\Graphpinator\Type\Scalar\ScalarType::class, $float);
        self::assertInstanceOf(\Infinityloop\Graphpinator\Type\Scalar\ScalarType::class, $string);
        self::assertInstanceOf(\Infinityloop\Graphpinator\Type\Scalar\ScalarType::class, $boolean);
        self::assertInstanceOf(\Infinityloop\Graphpinator\Type\Scalar\ScalarType::class, $id);
    }

    public function testResolveFields(): void
    {
        $type = new \Infinityloop\Graphpinator\Type\Scalar\BooleanType();
        $value = new \Infinityloop\Graphpinator\Value\ScalarValue(true, $type);
        $result = \Infinityloop\Graphpinator\Field\ResolveResult::fromValidated($value);

        self::assertSame($value, $type->resolveFields(null, $result));
    }

    public function testResolveFieldsInvalid(): void
    {
        $this->expectException(\Exception::class);

        $type = new \Infinityloop\Graphpinator\Type\Scalar\BooleanType();
        $value = new \Infinityloop\Graphpinator\Value\ScalarValue(true, $type);
        $result = \Infinityloop\Graphpinator\Field\ResolveResult::fromValidated($value);

        $type->resolveFields(new \Infinityloop\Graphpinator\Parser\RequestFieldSet([]), $result);
    }
}
