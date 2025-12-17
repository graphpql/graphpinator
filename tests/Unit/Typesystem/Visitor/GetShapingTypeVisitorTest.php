<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem\Visitor;

use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\EnumItem\EnumItemSet;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\TypeSet;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Typesystem\Visitor\GetShapingTypeVisitor;
use PHPUnit\Framework\TestCase;

final class GetShapingTypeVisitorTest extends TestCase
{
    private static ?Type $simpleType = null;
    private static ?InterfaceType $interface = null;
    private static ?UnionType $union = null;
    private static ?ScalarType $scalar = null;
    private static ?EnumType $enum = null;
    private static ?InputType $input = null;

    public static function setUpBeforeClass() : void
    {
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

        self::$interface = new class extends InterfaceType {
            protected const NAME = 'SimpleInterface';

            public function createResolvedValue($rawValue) : never
            {
                throw new \LogicException();
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([]);
            }
        };

        self::$union = new class (self::$simpleType) extends UnionType {
            protected const NAME = 'SimpleUnion';

            public function __construct(
                Type $type,
            )
            {
                parent::__construct(new TypeSet([$type]));
            }

            public function createResolvedValue(mixed $rawValue) : never
            {
                throw new \LogicException();
            }
        };

        self::$scalar = new class extends ScalarType {
            protected const NAME = 'CustomScalar';

            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }
        };

        self::$enum = new class extends EnumType {
            protected const NAME = 'SimpleEnum';

            public function __construct()
            {
                parent::__construct(new EnumItemSet([]));
            }

            protected function getEnumItems() : EnumItemSet
            {
                return new EnumItemSet([]);
            }
        };

        self::$input = new class extends InputType {
            protected const NAME = 'SimpleInput';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([]);
            }
        };
    }

    public function testType() : void
    {
        $visitor = new GetShapingTypeVisitor();
        $result = self::$simpleType->accept($visitor);

        self::assertSame(self::$simpleType, $result);
    }

    public function testInterface() : void
    {
        $visitor = new GetShapingTypeVisitor();
        $result = self::$interface->accept($visitor);

        self::assertSame(self::$interface, $result);
    }

    public function testUnion() : void
    {
        $visitor = new GetShapingTypeVisitor();
        $result = self::$union->accept($visitor);

        self::assertSame(self::$union, $result);
    }

    public function testScalar() : void
    {
        $visitor = new GetShapingTypeVisitor();
        $result = self::$scalar->accept($visitor);

        self::assertSame(self::$scalar, $result);
    }

    public function testEnum() : void
    {
        $visitor = new GetShapingTypeVisitor();
        $result = self::$enum->accept($visitor);

        self::assertSame(self::$enum, $result);
    }

    public function testInput() : void
    {
        $visitor = new GetShapingTypeVisitor();
        $result = self::$input->accept($visitor);

        self::assertSame(self::$input, $result);
    }

    public function testNotNull() : void
    {
        $visitor = new GetShapingTypeVisitor();
        $notNull = new NotNullType(self::$simpleType);
        $result = $notNull->accept($visitor);

        self::assertSame(self::$simpleType, $result);
    }

    public function testNestedNotNull() : void
    {
        $visitor = new GetShapingTypeVisitor();
        $notNull = new NotNullType(new NotNullType(self::$simpleType));
        $result = $notNull->accept($visitor);

        self::assertSame(self::$simpleType, $result);
    }

    public function testList() : void
    {
        $visitor = new GetShapingTypeVisitor();
        $list = new ListType(self::$simpleType);
        $result = $list->accept($visitor);

        self::assertSame($list, $result);
    }

    public function testNestedList() : void
    {
        $visitor = new GetShapingTypeVisitor();
        $innerList = new ListType(self::$simpleType);
        $outerList = new ListType($innerList);
        $result = $outerList->accept($visitor);

        self::assertSame($outerList, $result);
    }

    public function testNotNullList() : void
    {
        $visitor = new GetShapingTypeVisitor();
        $list = new ListType(self::$simpleType);
        $notNull = new NotNullType($list);
        $result = $notNull->accept($visitor);

        self::assertSame($list, $result);
    }

    public function testListNotNull() : void
    {
        $visitor = new GetShapingTypeVisitor();
        $notNull = new NotNullType(self::$simpleType);
        $list = new ListType($notNull);
        $result = $list->accept($visitor);

        self::assertSame($list, $result);
    }

    public function testComplexNesting() : void
    {
        $visitor = new GetShapingTypeVisitor();
        // [[Type!]!]!
        $innerList = new ListType(new NotNullType(self::$simpleType));
        $outerList = new ListType(new NotNullType($innerList));
        $complex = new NotNullType($outerList);
        $result = $complex->accept($visitor);

        self::assertSame($outerList, $result);
    }
}
