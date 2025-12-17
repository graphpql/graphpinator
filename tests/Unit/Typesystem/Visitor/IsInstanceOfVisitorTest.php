<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem\Visitor;

use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Contract\Type as TypeContract;
use Graphpinator\Typesystem\EnumItem\EnumItem;
use Graphpinator\Typesystem\EnumItem\EnumItemSet;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceSet;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\TypeSet;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Typesystem\Visitor\IsInstanceOfVisitor;
use Graphpinator\Value\TypeIntermediateValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class IsInstanceOfVisitorTest extends TestCase
{
    public static ?Type $typeImplementingInterface1 = null;
    public static ?Type $typeImplementingBothInterfaces = null;
    private static ?Type $simpleType = null;
    private static ?Type $anotherType = null;
    private static ?InterfaceType $interface1 = null;
    private static ?InterfaceType $interface2 = null;
    private static ?UnionType $union = null;
    private static ?ScalarType $customScalar = null;
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
                    new ResolvableField(
                        'id',
                        Container::Int(),
                        static fn() => 1,
                    ),
                ]);
            }
        };

        self::$anotherType = new class extends Type {
            protected const NAME = 'AnotherType';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'name',
                        Container::String(),
                        static fn() => 'test',
                    ),
                ]);
            }
        };

        self::$interface1 = new class extends InterfaceType {
            protected const NAME = 'Interface1';

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
                return new TypeIntermediateValue(IsInstanceOfVisitorTest::$typeImplementingInterface1, $rawValue);
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field('field1', Container::String()),
                ]);
            }
        };

        self::$interface2 = new class extends InterfaceType {
            protected const NAME = 'Interface2';

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
                return new TypeIntermediateValue(IsInstanceOfVisitorTest::$typeImplementingBothInterfaces, $rawValue);
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field('field2', Container::Int()),
                ]);
            }
        };

        self::$typeImplementingInterface1 = new class (self::$interface1) extends Type {
            protected const NAME = 'TypeImplementingInterface1';

            public function __construct(
                InterfaceType $interface,
            )
            {
                parent::__construct(new InterfaceSet([$interface]));
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField('field1', Container::String(), static fn() => 'value'),
                    new ResolvableField('extra', Container::Int(), static fn() => 42),
                ]);
            }
        };

        self::$typeImplementingBothInterfaces = new class (self::$interface1, self::$interface2) extends Type {
            protected const NAME = 'TypeImplementingBothInterfaces';

            public function __construct(
                InterfaceType $interface1,
                InterfaceType $interface2,
            )
            {
                parent::__construct(new InterfaceSet([$interface1, $interface2]));
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField('field1', Container::String(), static fn() => 'value'),
                    new ResolvableField('field2', Container::Int(), static fn() => 42),
                ]);
            }
        };

        self::$union = new class (self::$simpleType, self::$anotherType, self::$typeImplementingInterface1) extends UnionType {
            protected const NAME = 'SimpleUnion';

            public function __construct(
                Type $type1,
                Type $type2,
                Type $type3,
            )
            {
                parent::__construct(new TypeSet([$type1, $type2, $type3]));
            }

            public function createResolvedValue(mixed $rawValue) : never
            {
                throw new \LogicException();
            }
        };

        self::$customScalar = new class extends ScalarType {
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
                return new EnumItemSet([
                    new EnumItem('VALUE1'),
                    new EnumItem('VALUE2'),
                ]);
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

    public static function instanceOfDataProvider() : array
    {
        self::setUpBeforeClass();

        return [
            'same type' => [self::$simpleType, self::$simpleType, true],
            'different types' => [self::$simpleType, self::$anotherType, false],
            'NotNull is instance of nullable' => [
                new NotNullType(self::$simpleType),
                self::$simpleType,
                true,
            ],
            'nullable is NOT instance of NotNull' => [
                self::$simpleType,
                new NotNullType(self::$simpleType),
                false,
            ],
            'NotNull is instance of NotNull same type' => [
                new NotNullType(self::$simpleType),
                new NotNullType(self::$simpleType),
                true,
            ],
            'NotNull different types' => [
                new NotNullType(self::$simpleType),
                new NotNullType(self::$anotherType),
                false,
            ],
            'NotNull(NotNull(Type)) vs Type' => [
                new NotNullType(new NotNullType(self::$simpleType)),
                self::$simpleType,
                true,
            ],
            'same list types' => [
                new ListType(self::$simpleType),
                new ListType(self::$simpleType),
                true,
            ],
            'different list types' => [
                new ListType(self::$simpleType),
                new ListType(self::$anotherType),
                false,
            ],
            'list is NOT instance of non-list' => [
                new ListType(self::$simpleType),
                self::$simpleType,
                false,
            ],
            'non-list is NOT instance of list' => [
                self::$simpleType,
                new ListType(self::$simpleType),
                false,
            ],
            '[Int!] vs [Int]' => [
                new ListType(new NotNullType(self::$simpleType)),
                new ListType(self::$simpleType),
                true,
            ],
            '[Int] vs [Int!]' => [
                new ListType(self::$simpleType),
                new ListType(new NotNullType(self::$simpleType)),
                false,
            ],
            '[Int]! vs [Int]' => [
                new NotNullType(new ListType(self::$simpleType)),
                new ListType(self::$simpleType),
                true,
            ],
            '[Int] vs [Int]!' => [
                new ListType(self::$simpleType),
                new NotNullType(new ListType(self::$simpleType)),
                false,
            ],
            '[Int!]! vs [Int]' => [
                new NotNullType(new ListType(new NotNullType(self::$simpleType))),
                new ListType(self::$simpleType),
                true,
            ],
            '[Int]! vs [Int!]' => [
                new NotNullType(new ListType(self::$simpleType)),
                new ListType(new NotNullType(self::$simpleType)),
                false,
            ],
            'type implementing interface is instance of interface' => [
                self::$typeImplementingInterface1,
                self::$interface1,
                true,
            ],
            'interface is NOT instance of type implementing it' => [
                self::$interface1,
                self::$typeImplementingInterface1,
                false,
            ],
            'type implementing both interfaces is instance of first' => [
                self::$typeImplementingBothInterfaces,
                self::$interface1,
                true,
            ],
            'type implementing both interfaces is instance of second' => [
                self::$typeImplementingBothInterfaces,
                self::$interface2,
                true,
            ],
            'type implementing interface1 is NOT instance of interface2' => [
                self::$typeImplementingInterface1,
                self::$interface2,
                false,
            ],
            'Type! implementing Interface vs Interface' => [
                new NotNullType(self::$typeImplementingInterface1),
                self::$interface1,
                true,
            ],
            'Type implementing Interface vs Interface!' => [
                self::$typeImplementingInterface1,
                new NotNullType(self::$interface1),
                false,
            ],
            'same union' => [self::$union, self::$union, true],
            'union is NOT instance of its member' => [self::$union, self::$simpleType, false],
            'union member is instance of union' => [self::$simpleType, self::$union, true],
            'union is NOT instance of interface implementing one member' => [self::$union, self::$interface1, false],
            'interface implementing one member is NOT instance of union' => [self::$interface1, self::$union, false],
            'same scalar' => [self::$customScalar, self::$customScalar, true],
            'scalar vs type' => [self::$customScalar, self::$simpleType, false],
            'same enum' => [self::$enum, self::$enum, true],
            'enum vs type' => [self::$enum, self::$simpleType, false],
            'same input' => [self::$input, self::$input, true],
            'input vs type' => [self::$input, self::$simpleType, false],
            'same interface' => [self::$interface1, self::$interface1, true],
            'different interfaces' => [self::$interface1, self::$interface2, false],
            '[[Int!]!] vs [[Int]]' => [
                new ListType(new NotNullType(new ListType(new NotNullType(self::$simpleType)))),
                new ListType(new ListType(self::$simpleType)),
                true,
            ],
            '[[Int]] vs [[Int!]]' => [
                new ListType(new ListType(self::$simpleType)),
                new ListType(new ListType(new NotNullType(self::$simpleType))),
                false,
            ],
            'Type!! vs Type' => [
                new NotNullType(new NotNullType(self::$simpleType)),
                self::$simpleType,
                true,
            ],
            'Type!!! vs Type!' => [
                new NotNullType(new NotNullType(new NotNullType(self::$simpleType))),
                new NotNullType(self::$simpleType),
                true,
            ],
            'Interface! vs Interface' => [
                new NotNullType(self::$interface1),
                self::$interface1,
                true,
            ],
            'Interface vs Interface!' => [
                self::$interface1,
                new NotNullType(self::$interface1),
                false,
            ],
            'Interface!! vs Interface' => [
                new NotNullType(new NotNullType(self::$interface1)),
                self::$interface1,
                true,
            ],
            'Interface!! vs Interface!' => [
                new NotNullType(new NotNullType(self::$interface1)),
                new NotNullType(self::$interface1),
                true,
            ],
            'Union! vs Union' => [
                new NotNullType(self::$union),
                self::$union,
                true,
            ],
            'Union vs Union!' => [
                self::$union,
                new NotNullType(self::$union),
                false,
            ],
            'Union!! vs Union' => [
                new NotNullType(new NotNullType(self::$union)),
                self::$union,
                true,
            ],
            'Union!! vs Union!' => [
                new NotNullType(new NotNullType(self::$union)),
                new NotNullType(self::$union),
                true,
            ],
            'Scalar! vs Scalar' => [
                new NotNullType(self::$customScalar),
                self::$customScalar,
                true,
            ],
            'Scalar vs Scalar!' => [
                self::$customScalar,
                new NotNullType(self::$customScalar),
                false,
            ],
            'Scalar!! vs Scalar' => [
                new NotNullType(new NotNullType(self::$customScalar)),
                self::$customScalar,
                true,
            ],
            'Enum! vs Enum' => [
                new NotNullType(self::$enum),
                self::$enum,
                true,
            ],
            'Enum vs Enum!' => [
                self::$enum,
                new NotNullType(self::$enum),
                false,
            ],
            'Enum!! vs Enum!' => [
                new NotNullType(new NotNullType(self::$enum)),
                new NotNullType(self::$enum),
                true,
            ],
            'Input! vs Input' => [
                new NotNullType(self::$input),
                self::$input,
                true,
            ],
            'Input vs Input!' => [
                self::$input,
                new NotNullType(self::$input),
                false,
            ],
            'Type! vs AnotherType' => [
                new NotNullType(self::$simpleType),
                self::$anotherType,
                false,
            ],
            'Type! vs AnotherType!' => [
                new NotNullType(self::$simpleType),
                new NotNullType(self::$anotherType),
                false,
            ],
            'Type!! implementing Interface vs Interface' => [
                new NotNullType(new NotNullType(self::$typeImplementingInterface1)),
                self::$interface1,
                true,
            ],
            'Type!! implementing Interface vs Interface!' => [
                new NotNullType(new NotNullType(self::$typeImplementingInterface1)),
                new NotNullType(self::$interface1),
                true,
            ],
            'Type! implementing Interface vs Interface!!' => [
                new NotNullType(self::$typeImplementingInterface1),
                new NotNullType(new NotNullType(self::$interface1)),
                false,
            ],
            '[Type!]! vs [Type!]' => [
                new NotNullType(new ListType(new NotNullType(self::$simpleType))),
                new ListType(new NotNullType(self::$simpleType)),
                true,
            ],
            '[Type]!! vs [Type]' => [
                new NotNullType(new NotNullType(new ListType(self::$simpleType))),
                new ListType(self::$simpleType),
                true,
            ],
            '[Interface!] vs [Interface]' => [
                new ListType(new NotNullType(self::$interface1)),
                new ListType(self::$interface1),
                true,
            ],
            '[Union!] vs [Union]' => [
                new ListType(new NotNullType(self::$union)),
                new ListType(self::$union),
                true,
            ],
            '[Scalar!]! vs [Scalar]' => [
                new NotNullType(new ListType(new NotNullType(self::$customScalar))),
                new ListType(self::$customScalar),
                true,
            ],
            '[Enum!]! vs [Enum!]' => [
                new NotNullType(new ListType(new NotNullType(self::$enum))),
                new ListType(new NotNullType(self::$enum)),
                true,
            ],
            '[[Type!]!]! vs [[Type]]' => [
                new NotNullType(new ListType(new NotNullType(new ListType(new NotNullType(self::$simpleType))))),
                new ListType(new ListType(self::$simpleType)),
                true,
            ],
            '[[Interface!]]! vs [[Interface]]' => [
                new NotNullType(new ListType(new ListType(new NotNullType(self::$interface1)))),
                new ListType(new ListType(self::$interface1)),
                true,
            ],
            '[[[Type!]!]!] vs [[[Type]]]' => [
                new ListType(new NotNullType(new ListType(new NotNullType(new ListType(new NotNullType(self::$simpleType)))))),
                new ListType(new ListType(new ListType(self::$simpleType))),
                true,
            ],
        ];
    }

    #[DataProvider('instanceOfDataProvider')]
    public function testIsInstanceOf(
        TypeContract $visitedType,
        TypeContract $comparedType,
        bool $expectedResult,
    ) : void
    {
        $visitor = new IsInstanceOfVisitor($comparedType);
        $result = $visitedType->accept($visitor);

        self::assertSame($expectedResult, $result);
    }
}
