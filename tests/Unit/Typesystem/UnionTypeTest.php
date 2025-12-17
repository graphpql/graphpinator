<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem;

use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\TypeSet;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Value\TypeIntermediateValue;
use PHPUnit\Framework\TestCase;

final class UnionTypeTest extends TestCase
{
    public static function createTestUnion() : UnionType
    {
        return new class extends UnionType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct(
                    new TypeSet([
                        UnionTypeTest::getTestTypeXyz(),
                        UnionTypeTest::getTestTypeZzz(),
                    ]),
                );
            }

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
                return new TypeIntermediateValue(UnionTypeTest::getTestTypeXyz(), 123);
            }
        };
    }

    public static function getTestTypeAbc() : Type
    {
        return new class extends Type {
            protected const NAME = 'Abc';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet();
            }
        };
    }

    public static function getTestTypeXyz() : Type
    {
        return new class extends Type {
            protected const NAME = 'Xyz';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet();
            }
        };
    }

    public static function getTestTypeZzz() : Type
    {
        return new class extends Type {
            protected const NAME = 'Zzz';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet();
            }
        };
    }

    public function testSimple() : void
    {
        $union = self::createTestUnion();

        self::assertArrayHasKey('Xyz', $union->getTypes());
        self::assertSame('Xyz', $union->getTypes()->offsetGet('Xyz')->getName());
        self::assertArrayHasKey('Zzz', $union->getTypes());
        self::assertSame('Zzz', $union->getTypes()->offsetGet('Zzz')->getName());
    }
}
