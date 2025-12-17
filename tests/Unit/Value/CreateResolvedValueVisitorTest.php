<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Value;

use Graphpinator\ErrorHandlingMode;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\Location\TypeSystemDirectiveLocation;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\TypeSet;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Value\EnumValue;
use Graphpinator\Value\Exception\InvalidValue;
use Graphpinator\Value\Exception\ValueCannotBeNull;
use Graphpinator\Value\ListIntermediateValue;
use Graphpinator\Value\NullValue;
use Graphpinator\Value\ScalarValue;
use Graphpinator\Value\TypeIntermediateValue;
use Graphpinator\Value\Visitor\CreateResolvedValueVisitor;
use PHPUnit\Framework\TestCase;

final class CreateResolvedValueVisitorTest extends TestCase
{
    public static ?Type $simpleType = null;
    private static ?ScalarType $stringType = null;
    private static ?ScalarType $intType = null;
    private static ?EnumType $enum = null;
    private static ?InterfaceType $interface = null;
    private static ?UnionType $union = null;

    public static function setUpBeforeClass() : void
    {
        self::$stringType = Container::String();
        self::$intType = Container::Int();

        self::$simpleType = new class extends Type {
            protected const NAME = 'SimpleType';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField('id', Container::Int(), static fn() => 1),
                ]);
            }
        };

        self::$enum = new class extends EnumType {
            protected const NAME = 'TestEnum';

            public function __construct()
            {
                parent::__construct(EnumType::fromEnum(TypeSystemDirectiveLocation::class));
            }
        };

        self::$interface = new class extends InterfaceType {
            protected const NAME = 'TestInterface';

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
                return new TypeIntermediateValue(CreateResolvedValueVisitorTest::$simpleType, $rawValue);
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([]);
            }
        };

        self::$union = new class (self::$simpleType) extends UnionType {
            protected const NAME = 'TestUnion';

            public function __construct(
                Type $type,
            )
            {
                parent::__construct(new TypeSet([$type]));
            }

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
                return new TypeIntermediateValue(CreateResolvedValueVisitorTest::$simpleType, $rawValue);
            }
        };
    }

    public function testTypeWithValue() : void
    {
        $visitor = new CreateResolvedValueVisitor(['id' => 1]);
        $result = self::$simpleType->accept($visitor);

        self::assertInstanceOf(TypeIntermediateValue::class, $result);
    }

    public function testTypeWithNull() : void
    {
        $visitor = new CreateResolvedValueVisitor(null);
        $result = self::$simpleType->accept($visitor);

        self::assertInstanceOf(NullValue::class, $result);
    }

    public function testScalarWithValue() : void
    {
        $visitor = new CreateResolvedValueVisitor('test');
        $result = self::$stringType->accept($visitor);

        self::assertInstanceOf(ScalarValue::class, $result);
        self::assertSame('test', $result->getRawValue());
    }

    public function testScalarWithNull() : void
    {
        $visitor = new CreateResolvedValueVisitor(null);
        $result = self::$stringType->accept($visitor);

        self::assertInstanceOf(NullValue::class, $result);
    }

    public function testEnumWithValue() : void
    {
        $visitor = new CreateResolvedValueVisitor('OBJECT');
        $result = self::$enum->accept($visitor);

        self::assertInstanceOf(EnumValue::class, $result);
        self::assertSame(TypeSystemDirectiveLocation::OBJECT, $result->getRawValue());
    }

    public function testEnumWithNull() : void
    {
        $visitor = new CreateResolvedValueVisitor(null);
        $result = self::$enum->accept($visitor);

        self::assertInstanceOf(NullValue::class, $result);
    }

    public function testEnumWithBackedEnum() : void
    {
        $visitor = new CreateResolvedValueVisitor(TypeSystemDirectiveLocation::INPUT_OBJECT);
        $result = self::$enum->accept($visitor);

        self::assertInstanceOf(EnumValue::class, $result);
        self::assertSame(TypeSystemDirectiveLocation::INPUT_OBJECT, $result->getRawValue());
    }

    public function testInterfaceWithValue() : void
    {
        $visitor = new CreateResolvedValueVisitor(['id' => 1]);
        $result = self::$interface->accept($visitor);

        self::assertInstanceOf(TypeIntermediateValue::class, $result);
    }

    public function testInterfaceWithNull() : void
    {
        $visitor = new CreateResolvedValueVisitor(null);
        $result = self::$interface->accept($visitor);

        self::assertInstanceOf(NullValue::class, $result);
    }

    public function testUnionWithValue() : void
    {
        $visitor = new CreateResolvedValueVisitor(['id' => 1]);
        $result = self::$union->accept($visitor);

        self::assertInstanceOf(TypeIntermediateValue::class, $result);
    }

    public function testUnionWithNull() : void
    {
        $visitor = new CreateResolvedValueVisitor(null);
        $result = self::$union->accept($visitor);

        self::assertInstanceOf(NullValue::class, $result);
    }

    public function testNotNullWithValue() : void
    {
        $notNull = new NotNullType(self::$stringType);
        $visitor = new CreateResolvedValueVisitor('test');
        $result = $notNull->accept($visitor);

        self::assertInstanceOf(ScalarValue::class, $result);
    }

    public function testNotNullWithNull() : void
    {
        $this->expectException(ValueCannotBeNull::class);

        $notNull = new NotNullType(self::$stringType);
        $visitor = new CreateResolvedValueVisitor(null);
        $notNull->accept($visitor);
    }

    public function testListWithArray() : void
    {
        $list = new ListType(self::$intType);
        $visitor = new CreateResolvedValueVisitor([1, 2, 3]);
        $result = $list->accept($visitor);

        self::assertInstanceOf(ListIntermediateValue::class, $result);
    }

    public function testListWithNull() : void
    {
        $list = new ListType(self::$intType);
        $visitor = new CreateResolvedValueVisitor(null);
        $result = $list->accept($visitor);

        self::assertInstanceOf(NullValue::class, $result);
    }

    public function testListWithNonIterable() : void
    {
        $this->expectException(InvalidValue::class);

        $list = new ListType(self::$intType);
        $visitor = new CreateResolvedValueVisitor('not iterable');
        $list->accept($visitor);
    }

    public function testListWithGenerator() : void
    {
        $generator = (static function () {
            yield 1;
            yield 2;
            yield 3;
        })();

        $list = new ListType(self::$intType);
        $visitor = new CreateResolvedValueVisitor($generator);
        $result = $list->accept($visitor);

        self::assertInstanceOf(ListIntermediateValue::class, $result);
    }

    public function testNestedList() : void
    {
        $list = new ListType(new ListType(self::$intType));
        $visitor = new CreateResolvedValueVisitor([[1, 2], [3, 4]]);
        $result = $list->accept($visitor);

        self::assertInstanceOf(ListIntermediateValue::class, $result);
    }

    public function testNotNullListWithValue() : void
    {
        $notNullList = new NotNullType(new ListType(self::$intType));
        $visitor = new CreateResolvedValueVisitor([1, 2, 3]);
        $result = $notNullList->accept($visitor);

        self::assertInstanceOf(ListIntermediateValue::class, $result);
    }

    public function testNotNullListWithNull() : void
    {
        $this->expectException(ValueCannotBeNull::class);

        $notNullList = new NotNullType(new ListType(self::$intType));
        $visitor = new CreateResolvedValueVisitor(null);
        $notNullList->accept($visitor);
    }

    public function testListOfTypes() : void
    {
        $list = new ListType(self::$simpleType);
        $visitor = new CreateResolvedValueVisitor([
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
        ]);
        $result = $list->accept($visitor);

        self::assertInstanceOf(ListIntermediateValue::class, $result);
    }
}
