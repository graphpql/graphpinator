<?php

declare(strict_types=1);

namespace Tests\Type\Scalar;

final class ScalarTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testCreate(): void
    {
        $int = \PGQL\Type\Scalar\ScalarType::Int();
        $float = \PGQL\Type\Scalar\ScalarType::Float();
        $string = \PGQL\Type\Scalar\ScalarType::String();
        $boolean = \PGQL\Type\Scalar\ScalarType::Boolean();
        $id = \PGQL\Type\Scalar\ScalarType::ID();

        self::assertInstanceOf(\PGQL\Type\Scalar\ScalarType::class, $int);
        self::assertInstanceOf(\PGQL\Type\Scalar\ScalarType::class, $float);
        self::assertInstanceOf(\PGQL\Type\Scalar\ScalarType::class, $string);
        self::assertInstanceOf(\PGQL\Type\Scalar\ScalarType::class, $boolean);
        self::assertInstanceOf(\PGQL\Type\Scalar\ScalarType::class, $id);
    }

    public function testResolveFields(): void
    {
        $type = new \PGQL\Type\Scalar\BooleanType();
        $value = new \PGQL\Value\ValidatedValue(true, $type);
        $result = \PGQL\Field\ResolveResult::fromValidated($value);

        self::assertSame($value, $type->resolveFields(null, $result));
    }

    public function testResolveFieldsInvalid(): void
    {
        $this->expectException(\Exception::class);

        $type = new \PGQL\Type\Scalar\BooleanType();
        $value = new \PGQL\Value\ValidatedValue(true, $type);
        $result = \PGQL\Field\ResolveResult::fromValidated($value);

        $type->resolveFields([], $result);
    }
}
