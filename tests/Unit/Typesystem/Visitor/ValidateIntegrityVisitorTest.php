<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem\Visitor;

use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Directive;
use Graphpinator\Typesystem\EnumItem\EnumItem;
use Graphpinator\Typesystem\EnumItem\EnumItemSet;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\Exception\ArgumentInvalidTypeUsage;
use Graphpinator\Typesystem\Exception\DirectiveIncorrectType;
use Graphpinator\Typesystem\Exception\DuplicateNonRepeatableDirective;
use Graphpinator\Typesystem\Exception\EnumItemInvalid;
use Graphpinator\Typesystem\Exception\FieldInvalidTypeUsage;
use Graphpinator\Typesystem\Exception\FieldResolverNotIterable;
use Graphpinator\Typesystem\Exception\FieldResolverNullabilityMismatch;
use Graphpinator\Typesystem\Exception\InputCycleDetected;
use Graphpinator\Typesystem\Exception\InputTypeMustDefineOneOreMoreFields;
use Graphpinator\Typesystem\Exception\InterfaceContractArgumentTypeMismatch;
use Graphpinator\Typesystem\Exception\InterfaceContractFieldTypeMismatch;
use Graphpinator\Typesystem\Exception\InterfaceContractMissingArgument;
use Graphpinator\Typesystem\Exception\InterfaceContractMissingField;
use Graphpinator\Typesystem\Exception\InterfaceContractNewArgumentWithoutDefault;
use Graphpinator\Typesystem\Exception\InterfaceCycleDetected;
use Graphpinator\Typesystem\Exception\InterfaceOrTypeMustDefineOneOrMoreFields;
use Graphpinator\Typesystem\Exception\RootOperationTypesMustBeDifferent;
use Graphpinator\Typesystem\Exception\RootOperationTypesMustBeWithinContainer;
use Graphpinator\Typesystem\Exception\UnionTypeMustDefineOneOrMoreTypes;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\InterfaceSet;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\Location\ObjectLocation;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\TypeSet;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Typesystem\Visitor\ValidateIntegrityVisitor;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\TypeValue;
use PHPUnit\Framework\TestCase;

final class ValidateIntegrityVisitorTest extends TestCase
{
    public function testTypeWithNoFields() : void
    {
        $type = new class extends Type {
            protected const NAME = 'EmptyType';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([]);
            }
        };

        $this->expectException(InterfaceOrTypeMustDefineOneOrMoreFields::class);
        $type->accept(new ValidateIntegrityVisitor());
    }

    public function testInterfaceWithNoFields() : void
    {
        $interface = new class extends InterfaceType {
            protected const NAME = 'EmptyInterface';

            public function createResolvedValue($rawValue) : never
            {
                throw new \LogicException();
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([]);
            }
        };

        $this->expectException(InterfaceOrTypeMustDefineOneOrMoreFields::class);
        $interface->accept(new ValidateIntegrityVisitor());
    }

    public function testInputWithNoFields() : void
    {
        $input = new class extends InputType {
            protected const NAME = 'EmptyInput';

            public function validateAndCoerceInput(mixed $rawValue) : mixed
            {
                return $rawValue;
            }

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([]);
            }
        };

        $this->expectException(InputTypeMustDefineOneOreMoreFields::class);
        $input->accept(new ValidateIntegrityVisitor());
    }

    public function testUnionWithNoTypes() : void
    {
        $union = new class extends UnionType {
            protected const NAME = 'EmptyUnion';

            public function __construct()
            {
                parent::__construct(new TypeSet([]));
            }

            public function createResolvedValue(mixed $rawValue) : never
            {
                throw new \LogicException();
            }
        };

        $this->expectException(UnionTypeMustDefineOneOrMoreTypes::class);
        $union->accept(new ValidateIntegrityVisitor());
    }

    public function testInterfaceContractMissingField() : void
    {
        $interface = new class extends InterfaceType {
            protected const NAME = 'TestInterface';

            public function createResolvedValue($rawValue) : never
            {
                throw new \LogicException();
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field('requiredField', Container::String()),
                ]);
            }
        };

        $type = new class ($interface) extends Type {
            protected const NAME = 'TestType';

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
                    new ResolvableField('otherField', Container::String(), static fn() => 'value'),
                ]);
            }
        };

        $this->expectException(InterfaceContractMissingField::class);
        $type->accept(new ValidateIntegrityVisitor());
    }

    public function testInterfaceContractFieldTypeMismatch() : void
    {
        $interface = new class extends InterfaceType {
            protected const NAME = 'TestInterface';

            public function createResolvedValue($rawValue) : never
            {
                throw new \LogicException();
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field('field', Container::String()),
                ]);
            }
        };

        $type = new class ($interface) extends Type {
            protected const NAME = 'TestType';

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
                    new ResolvableField('field', Container::Int(), static fn() => 1),
                ]);
            }
        };

        $this->expectException(InterfaceContractFieldTypeMismatch::class);
        $type->accept(new ValidateIntegrityVisitor());
    }

    public function testInterfaceContractMissingArgument() : void
    {
        $interface = new class extends InterfaceType {
            protected const NAME = 'TestInterface';

            public function createResolvedValue($rawValue) : never
            {
                throw new \LogicException();
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    (new Field('field', Container::String()))
                        ->setArguments(new ArgumentSet([
                            new Argument('requiredArg', Container::String()),
                        ])),
                ]);
            }
        };

        $type = new class ($interface) extends Type {
            protected const NAME = 'TestType';

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
                    (new ResolvableField('field', Container::String(), static fn() => 'value'))
                        ->setArguments(new ArgumentSet([
                            new Argument('differentArg', Container::String()),
                        ])),
                ]);
            }
        };

        $this->expectException(InterfaceContractMissingArgument::class);
        $type->accept(new ValidateIntegrityVisitor());
    }

    public function testInterfaceContractArgumentTypeMismatch() : void
    {
        $interface = new class extends InterfaceType {
            protected const NAME = 'TestInterface';

            public function createResolvedValue($rawValue) : never
            {
                throw new \LogicException();
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    (new Field('field', Container::String()))
                        ->setArguments(new ArgumentSet([
                            new Argument('argName', Container::String()),
                        ])),
                ]);
            }
        };

        $type = new class ($interface) extends Type {
            protected const NAME = 'TestType';

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
                    (new ResolvableField('field', Container::String(), static fn() => 'value'))
                        ->setArguments(new ArgumentSet([
                            new Argument('argName', Container::Int()),
                        ])),
                ]);
            }
        };

        $this->expectException(InterfaceContractArgumentTypeMismatch::class);
        $type->accept(new ValidateIntegrityVisitor());
    }

    public function testInterfaceContractNewArgumentWithoutDefault() : void
    {
        $interface = new class extends InterfaceType {
            protected const NAME = 'TestInterface';

            public function createResolvedValue($rawValue) : never
            {
                throw new \LogicException();
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field('field', Container::String()),
                ]);
            }
        };

        $type = new class ($interface) extends Type {
            protected const NAME = 'TestType';

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
                    (new ResolvableField('field', Container::String(), static fn() => 'value'))
                        ->setArguments(new ArgumentSet([
                            new Argument('newArg', new NotNullType(Container::String())),
                        ])),
                ]);
            }
        };

        $this->expectException(InterfaceContractNewArgumentWithoutDefault::class);
        $type->accept(new ValidateIntegrityVisitor());
    }

    public function testInputCycleDetected() : void
    {
        $inputA = new class extends InputType {
            protected const NAME = 'InputA';

            public InputType|null $inputB = null;

            public function validateAndCoerceInput(mixed $rawValue) : mixed
            {
                return $rawValue;
            }

            protected function getFieldDefinition() : ArgumentSet
            {
                if ($this->inputB === null) {
                    return new ArgumentSet([
                        new Argument('field', Container::String()),
                    ]);
                }

                return new ArgumentSet([
                    new Argument('field', new NotNullType($this->inputB)),
                ]);
            }
        };

        $inputB = new class ($inputA) extends InputType {
            protected const NAME = 'InputB';

            public function __construct(
                private InputType $inputA,
            )
            {
                parent::__construct();
            }

            public function validateAndCoerceInput(mixed $rawValue) : mixed
            {
                return $rawValue;
            }

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument('field', new NotNullType($this->inputA)),
                ]);
            }
        };

        $inputA->inputB = $inputB;

        $this->expectException(InputCycleDetected::class);
        $inputA->accept(new ValidateIntegrityVisitor());
    }

    public function testValidType() : void
    {
        $type = new class extends Type {
            protected const NAME = 'ValidType';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField('id', Container::Int(), static fn() => 1),
                    new ResolvableField('name', Container::String(), static fn() => 'test'),
                ]);
            }
        };

        $result = $type->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testValidInterface() : void
    {
        $interface = new class extends InterfaceType {
            protected const NAME = 'ValidInterface';

            public function createResolvedValue($rawValue) : never
            {
                throw new \LogicException();
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field('id', Container::Int()),
                ]);
            }
        };

        $result = $interface->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testValidInput() : void
    {
        $input = new class extends InputType {
            protected const NAME = 'ValidInput';

            public function validateAndCoerceInput(mixed $rawValue) : mixed
            {
                return $rawValue;
            }

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument('name', Container::String()),
                ]);
            }
        };

        $result = $input->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testValidScalar() : void
    {
        $scalar = new class extends ScalarType {
            protected const NAME = 'CustomScalar';

            public function validateAndCoerceInput(mixed $rawValue) : mixed
            {
                return $rawValue;
            }

            public function coerceOutput(mixed $rawValue) : string|int|float|bool
            {
                return (string) $rawValue;
            }
        };

        $result = $scalar->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testValidEnum() : void
    {
        $enum = new class extends EnumType {
            protected const NAME = 'ValidEnum';

            public function __construct()
            {
                parent::__construct(new EnumItemSet([
                    new EnumItem('VALUE_ONE'),
                    new EnumItem('VALUE_TWO'),
                ]));
            }
        };

        $result = $enum->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testValidUnion() : void
    {
        $type = new class extends Type {
            protected const NAME = 'MemberType';

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

        $union = new class ($type) extends UnionType {
            protected const NAME = 'ValidUnion';

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

        $result = $union->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testValidInterfaceImplementation() : void
    {
        $interface = new class extends InterfaceType {
            protected const NAME = 'TestInterface';

            public function createResolvedValue($rawValue) : never
            {
                throw new \LogicException();
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    (new Field('field', Container::String()))
                        ->setArguments(new ArgumentSet([
                            new Argument('arg', Container::String()),
                        ])),
                ]);
            }
        };

        $type = new class ($interface) extends Type {
            protected const NAME = 'TestType';

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
                    (new ResolvableField('field', Container::String(), static fn() => 'value'))
                        ->setArguments(new ArgumentSet([
                            new Argument('arg', Container::String()),
                        ])),
                ]);
            }
        };

        $result = $type->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testFieldInvalidTypeUsage() : void
    {
        $input = new class extends InputType {
            protected const NAME = 'TestInput';

            public function validateAndCoerceInput(mixed $rawValue) : mixed
            {
                return $rawValue;
            }

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument('field', Container::String()),
                ]);
            }
        };

        $type = new class ($input) extends Type {
            protected const NAME = 'InvalidType';

            public function __construct(
                private InputType $input,
            )
            {
                parent::__construct();
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                // Field with input type (not outputable)
                return new ResolvableFieldSet([
                    new ResolvableField('field', $this->input->notNullList(), static fn() => null),
                ]);
            }
        };

        $this->expectException(FieldInvalidTypeUsage::class);
        $type->accept(new ValidateIntegrityVisitor());
    }

    public function testArgumentInvalidTypeUsage() : void
    {
        $type = new class extends Type {
            protected const NAME = 'TestType';

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

        $argument = Argument::create('name', $type->notNull());

        $this->expectException(ArgumentInvalidTypeUsage::class);
        $argument->accept(new ValidateIntegrityVisitor());
    }

    public function testEnumItemInvalid() : void
    {
        $enum = new class extends EnumType {
            protected const NAME = 'InvalidEnum';

            public function __construct()
            {
                parent::__construct(new EnumItemSet([
                    new EnumItem('true'), // Reserved keyword
                ]));
            }
        };

        $this->expectException(EnumItemInvalid::class);
        $enum->accept(new ValidateIntegrityVisitor());
    }

    public function testRootOperationTypesMustBeDifferent() : void
    {
        $query = new class extends Type {
            protected const NAME = 'Query';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField('field', Container::String(), static fn() => 'value'),
                ]);
            }
        };

        $container = new SimpleContainer([$query], []);

        // Query and Mutation are the same type
        $schema = new Schema($container, $query, $query);

        $this->expectException(RootOperationTypesMustBeDifferent::class);
        $schema->accept(new ValidateIntegrityVisitor());
    }

    public function testRootOperationTypesMustBeWithinContainer() : void
    {
        $query = new class extends Type {
            protected const NAME = 'Query';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField('field', Container::String(), static fn() => 'value'),
                ]);
            }
        };

        $mutation = new class extends Type {
            protected const NAME = 'Mutation';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField('field', Container::String(), static fn() => 'value'),
                ]);
            }
        };

        $container = new SimpleContainer([$query], []);

        $schema = new Schema($container, $query, $mutation);

        $this->expectException(RootOperationTypesMustBeWithinContainer::class);
        $schema->accept(new ValidateIntegrityVisitor());
    }

    public function testInterfaceCycleDetectedSimple() : void
    {
        // This test uses reflection to create a cycle since we can't do it directly
        $interfaceA = new class extends InterfaceType {
            protected const NAME = 'InterfaceA';

            public function createResolvedValue($rawValue) : never
            {
                throw new \LogicException();
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field('field', Container::String()),
                ]);
            }
        };

        $interfaceB = new class extends InterfaceType {
            protected const NAME = 'InterfaceB';

            public function createResolvedValue($rawValue) : never
            {
                throw new \LogicException();
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field('field', Container::String()),
                ]);
            }
        };

        // Use reflection to create circular dependency
        $reflectionA = new \ReflectionClass($interfaceA);
        $implementsProperty = $reflectionA->getProperty('implements');
        $implementsProperty->setValue($interfaceA, new InterfaceSet([$interfaceB]));

        $reflectionB = new \ReflectionClass($interfaceB);
        $implementsProperty = $reflectionB->getProperty('implements');
        $implementsProperty->setValue($interfaceB, new InterfaceSet([$interfaceA]));

        $this->expectException(InterfaceCycleDetected::class);
        $interfaceA->accept(new ValidateIntegrityVisitor());
    }

    public function testDuplicateNonRepeatableDirectiveField() : void
    {
        $type = new class extends Type {
            protected const NAME = 'TestType';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                $field = new ResolvableField('field', Container::String(), static fn() => 'value');

                // Add the same non-repeatable directive twice
                $field->addDirective(Container::directiveDeprecated(), ['reason' => 'test1']);
                $field->addDirective(Container::directiveDeprecated(), ['reason' => 'test2']);

                return new ResolvableFieldSet([$field]);
            }
        };

        $this->expectException(DuplicateNonRepeatableDirective::class);
        $type->accept(new ValidateIntegrityVisitor());
    }

    public function testFieldResolverNullabilityMismatchNotNullFieldNullableReturn() : void
    {
        $field = new ResolvableField(
            'testField',
            Container::String()->notNull(),
            static fn() : ?string => null,
        );

        $this->expectException(FieldResolverNullabilityMismatch::class);
        $field->accept(new ValidateIntegrityVisitor());
    }

    public function testFieldResolverNullabilityMismatchNullableFieldNotNullReturn() : void
    {
        $field = new ResolvableField(
            'testField',
            Container::String(),
            static fn() : string => 'value',
        );

        $this->expectException(FieldResolverNullabilityMismatch::class);
        $field->accept(new ValidateIntegrityVisitor());
    }

    public function testFieldResolverNotIterable() : void
    {
        $field = new ResolvableField(
            'testField',
            Container::String()->list(),
            static fn() : ?string => 'not-iterable',
        );

        $this->expectException(FieldResolverNotIterable::class);
        $field->accept(new ValidateIntegrityVisitor());
    }

    public function testFieldResolverNotIterableInNotNull() : void
    {
        $field = new ResolvableField(
            'testField',
            Container::String()->list()->notNull(),
            static fn() : int => 123,
        );

        $this->expectException(FieldResolverNotIterable::class);
        $field->accept(new ValidateIntegrityVisitor());
    }

    public function testFieldResolverIterableArray() : void
    {
        $field = new ResolvableField(
            'testField',
            Container::String()->list(),
            static fn() : ?array => ['value'],
        );

        $result = $field->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testFieldResolverIterableIterable() : void
    {
        $field = new ResolvableField(
            'testField',
            Container::String()->list(),
            static fn() : ?iterable => ['value'],
        );

        $result = $field->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testFieldResolverUnionTypeAllIterable() : void
    {
        $field = new ResolvableField(
            'testField',
            Container::String()->list(),
            static fn() : array|\ArrayIterator|null => ['value'],
        );

        $result = $field->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testFieldResolverUnionTypeNotAllIterable() : void
    {
        $field = new ResolvableField(
            'testField',
            Container::String()->list(),
            static fn() : array|string|null => ['value'],
        );

        $this->expectException(FieldResolverNotIterable::class);
        $field->accept(new ValidateIntegrityVisitor());
    }

    public function testFieldResolverIntersectionTypeOneIterable() : void
    {
        $field = new ResolvableField(
            'testField',
            Container::String()->list(),
            static fn() : (\Countable&\Iterator)|null => new \ArrayIterator([]),
        );

        $result = $field->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testFieldResolverIntersectionTypeNoneIterable() : void
    {
        $field = new ResolvableField(
            'testField',
            Container::String()->list(),
            static fn() : (\Countable&\Stringable)|null => new class implements \Countable, \Stringable {
                public function count() : int
                {
                    return 0;
                }

                public function __toString() : string
                {
                    return '';
                }
            },
        );

        $this->expectException(FieldResolverNotIterable::class);
        $field->accept(new ValidateIntegrityVisitor());
    }

    public function testFieldResolverNoReturnType() : void
    {
        $field = new ResolvableField(
            'testField',
            Container::String()->notNull(),
            static fn() => 'value',
        );

        $result = $field->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testFieldResolverValidNullable() : void
    {
        $field = new ResolvableField(
            'testField',
            Container::String(),
            static fn() : ?string => null,
        );

        $result = $field->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testFieldResolverValidNotNull() : void
    {
        $field = new ResolvableField(
            'testField',
            Container::String()->notNull(),
            static fn() : string => 'value',
        );

        $result = $field->accept(new ValidateIntegrityVisitor());
        self::assertNull($result);
    }

    public function testTypeWithValidDirective() : void
    {
        $directive = new class extends Directive implements ObjectLocation {
            protected const NAME = 'testDirective';

            #[\Override]
            public function validateObjectUsage(Type|InterfaceType $type, ArgumentValueSet $arguments) : bool
            {
                return true;
            }

            #[\Override]
            public function resolveObject(ArgumentValueSet $arguments, TypeValue $typeValue) : void
            {
            }

            #[\Override]
            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([]);
            }
        };

        $type = new class ($directive) extends Type {
            protected const NAME = 'TestType';

            public function __construct(
                ObjectLocation $directive,
            )
            {
                parent::__construct();
                $this->addDirective($directive);
            }

            #[\Override]
            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            #[\Override]
            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField('field', Container::String(), static fn() => 'value'),
                ]);
            }
        };

        $type->accept(new ValidateIntegrityVisitor());

        $this->expectNotToPerformAssertions();
    }

    public function testTypeWithInvalidDirective() : void
    {
        $directive = new class extends Directive implements ObjectLocation {
            protected const NAME = 'testDirective';

            #[\Override]
            public function validateObjectUsage(Type|InterfaceType $type, ArgumentValueSet $arguments) : bool
            {
                return false;
            }

            #[\Override]
            public function resolveObject(ArgumentValueSet $arguments, TypeValue $typeValue) : void
            {
            }

            #[\Override]
            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([]);
            }
        };

        $type = new class ($directive) extends Type {
            protected const NAME = 'TestType';

            public function __construct(
                ObjectLocation $directive,
            )
            {
                parent::__construct();
                $this->addDirective($directive);
            }

            #[\Override]
            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            #[\Override]
            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField('field', Container::String(), static fn() => 'value'),
                ]);
            }
        };

        $this->expectException(DirectiveIncorrectType::class);
        $this->expectExceptionMessage(DirectiveIncorrectType::MESSAGE);
        $type->accept(new ValidateIntegrityVisitor());
    }
}
