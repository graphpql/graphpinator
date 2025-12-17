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
use Graphpinator\Typesystem\Visitor\PrintNameVisitor;
use PHPUnit\Framework\TestCase;

final class PrintNameVisitorTest extends TestCase
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
        $visitor = new PrintNameVisitor();
        $result = self::$simpleType->accept($visitor);

        self::assertSame('SimpleType', $result);
    }

    public function testInterface() : void
    {
        $visitor = new PrintNameVisitor();
        $result = self::$interface->accept($visitor);

        self::assertSame('SimpleInterface', $result);
    }

    public function testUnion() : void
    {
        $visitor = new PrintNameVisitor();
        $result = self::$union->accept($visitor);

        self::assertSame('SimpleUnion', $result);
    }

    public function testScalar() : void
    {
        $visitor = new PrintNameVisitor();
        $result = self::$scalar->accept($visitor);

        self::assertSame('CustomScalar', $result);
    }

    public function testEnum() : void
    {
        $visitor = new PrintNameVisitor();
        $result = self::$enum->accept($visitor);

        self::assertSame('SimpleEnum', $result);
    }

    public function testInput() : void
    {
        $visitor = new PrintNameVisitor();
        $result = self::$input->accept($visitor);

        self::assertSame('SimpleInput', $result);
    }

    public function testNotNull() : void
    {
        $visitor = new PrintNameVisitor();
        $notNull = new NotNullType(self::$simpleType);
        $result = $notNull->accept($visitor);

        self::assertSame('SimpleType!', $result);
    }

    public function testNestedNotNull() : void
    {
        $visitor = new PrintNameVisitor();
        $notNull = new NotNullType(new NotNullType(self::$simpleType));
        $result = $notNull->accept($visitor);

        self::assertSame('SimpleType!!', $result);
    }

    public function testList() : void
    {
        $visitor = new PrintNameVisitor();
        $list = new ListType(self::$simpleType);
        $result = $list->accept($visitor);

        self::assertSame('[SimpleType]', $result);
    }

    public function testNestedList() : void
    {
        $visitor = new PrintNameVisitor();
        $list = new ListType(new ListType(self::$simpleType));
        $result = $list->accept($visitor);

        self::assertSame('[[SimpleType]]', $result);
    }

    public function testNotNullList() : void
    {
        $visitor = new PrintNameVisitor();
        $notNull = new NotNullType(new ListType(self::$simpleType));
        $result = $notNull->accept($visitor);

        self::assertSame('[SimpleType]!', $result);
    }

    public function testListNotNull() : void
    {
        $visitor = new PrintNameVisitor();
        $list = new ListType(new NotNullType(self::$simpleType));
        $result = $list->accept($visitor);

        self::assertSame('[SimpleType!]', $result);
    }

    public function testComplexNesting() : void
    {
        $visitor = new PrintNameVisitor();
        // [[Type!]!]!
        $complex = new NotNullType(
            new ListType(
                new NotNullType(
                    new ListType(
                        new NotNullType(self::$simpleType),
                    ),
                ),
            ),
        );
        $result = $complex->accept($visitor);

        self::assertSame('[[SimpleType!]!]!', $result);
    }

    public function testListOfInterface() : void
    {
        $visitor = new PrintNameVisitor();
        $list = new ListType(self::$interface);
        $result = $list->accept($visitor);

        self::assertSame('[SimpleInterface]', $result);
    }

    public function testListOfUnion() : void
    {
        $visitor = new PrintNameVisitor();
        $list = new ListType(self::$union);
        $result = $list->accept($visitor);

        self::assertSame('[SimpleUnion]', $result);
    }

    public function testTripleNestedNotNull() : void
    {
        $visitor = new PrintNameVisitor();
        $notNull = new NotNullType(new NotNullType(new NotNullType(self::$scalar)));
        $result = $notNull->accept($visitor);

        self::assertSame('CustomScalar!!!', $result);
    }
}
