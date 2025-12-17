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
use Graphpinator\Typesystem\Visitor\IsInputableVisitor;
use PHPUnit\Framework\TestCase;

final class IsInputableVisitorTest extends TestCase
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
        $visitor = new IsInputableVisitor();
        $result = self::$simpleType->accept($visitor);

        self::assertFalse($result);
    }

    public function testInterface() : void
    {
        $visitor = new IsInputableVisitor();
        $result = self::$interface->accept($visitor);

        self::assertFalse($result);
    }

    public function testUnion() : void
    {
        $visitor = new IsInputableVisitor();
        $result = self::$union->accept($visitor);

        self::assertFalse($result);
    }

    public function testScalar() : void
    {
        $visitor = new IsInputableVisitor();
        $result = self::$scalar->accept($visitor);

        self::assertTrue($result);
    }

    public function testEnum() : void
    {
        $visitor = new IsInputableVisitor();
        $result = self::$enum->accept($visitor);

        self::assertTrue($result);
    }

    public function testInput() : void
    {
        $visitor = new IsInputableVisitor();
        $result = self::$input->accept($visitor);

        self::assertTrue($result);
    }

    public function testNotNullScalar() : void
    {
        $visitor = new IsInputableVisitor();
        $notNull = new NotNullType(self::$scalar);
        $result = $notNull->accept($visitor);

        self::assertTrue($result);
    }

    public function testNotNullType() : void
    {
        $visitor = new IsInputableVisitor();
        $notNull = new NotNullType(self::$simpleType);
        $result = $notNull->accept($visitor);

        self::assertFalse($result);
    }

    public function testListOfScalar() : void
    {
        $visitor = new IsInputableVisitor();
        $list = new ListType(self::$scalar);
        $result = $list->accept($visitor);

        self::assertTrue($result);
    }

    public function testListOfType() : void
    {
        $visitor = new IsInputableVisitor();
        $list = new ListType(self::$simpleType);
        $result = $list->accept($visitor);

        self::assertFalse($result);
    }

    public function testListOfInput() : void
    {
        $visitor = new IsInputableVisitor();
        $list = new ListType(self::$input);
        $result = $list->accept($visitor);

        self::assertTrue($result);
    }

    public function testListOfEnum() : void
    {
        $visitor = new IsInputableVisitor();
        $list = new ListType(self::$enum);
        $result = $list->accept($visitor);

        self::assertTrue($result);
    }

    public function testComplexInputable() : void
    {
        $visitor = new IsInputableVisitor();
        // [[Scalar!]!]!
        $complex = new NotNullType(
            new ListType(
                new NotNullType(
                    new ListType(
                        new NotNullType(self::$scalar),
                    ),
                ),
            ),
        );
        $result = $complex->accept($visitor);

        self::assertTrue($result);
    }

    public function testComplexNonInputable() : void
    {
        $visitor = new IsInputableVisitor();
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

        self::assertFalse($result);
    }
}
