<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Directive;
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
use Graphpinator\Typesystem\Location\FieldLocation;
use Graphpinator\Typesystem\Location\SelectionDirectiveResult;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use Graphpinator\Typesystem\TypeSet;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Value\ArgumentValueSet;
use Graphpinator\Value\FieldValue;
use Graphpinator\Value\TypeIntermediateValue;

final class TestSchema
{
    private static array $types = [];
    private static ?Container $container = null;
    private static ?Type $query = null;

    public static function getSchema() : Schema
    {
        return new Schema(
            self::getContainer(),
            self::getQuery(),
        );
    }

    public static function getFullSchema() : Schema
    {
        $query = self::getQuery();

        return new Schema(
            self::getContainer(),
            $query,
            $query,
            $query,
        );
    }

    public static function getType(string $name) : object
    {
        if (\array_key_exists($name, self::$types)) {
            return self::$types[$name];
        }

        self::$types[$name] = match ($name) {
            'Query' => self::getQuery(),
            'Abc' => self::getTypeAbc(),
            'Xyz' => self::getTypeXyz(),
            'Zzz' => self::getTypeZzz(),
            'TestInterface' => self::getInterface(),
            'TestUnion' => self::getUnion(),
            'UnionInvalidResolvedType' => self::getUnionInvalidResolvedType(),
            'CompositeInput' => self::getCompositeInput(),
            'SimpleInput' => self::getSimpleInput(),
            'DefaultsInput' => self::getDefaultsInput(),
            'SimpleEnum' => self::getSimpleEnum(),
            'ArrayEnum' => self::getArrayEnum(),
            'DescriptionEnum' => self::getDescriptionEnum(),
            'TestScalar' => self::getTestScalar(),
            'ComplexDefaultsInput' => self::getComplexDefaultsInput(),
            'NullFieldResolution' => self::getNullFieldResolution(),
            'NullListResolution' => self::getNullListResolution(),
            'SimpleType' => self::getSimpleType(),
            'InterfaceAbc' => self::getInterfaceAbc(),
            'InterfaceEfg' => self::getInterfaceEfg(),
            'FragmentTypeA' => self::getFragmentTypeA(),
            'FragmentTypeB' => self::getFragmentTypeB(),
            'SimpleEmptyTestInput' => self::getSimpleEmptyTestInput(),
            'InterfaceChildType' => self::getInterfaceChildType(),
            'testDirective' => self::getTestDirective(),
            'invalidDirectiveType' => self::getInvalidDirectiveType(),
        };

        return self::$types[$name];
    }

    public static function getContainer() : Container
    {
        if (self::$container !== null) {
            return self::$container;
        }

        self::$container = new SimpleContainer([
            'Query' => self::getType('Query'),
            'Abc' => self::getType('Abc'),
            'Xyz' => self::getType('Xyz'),
            'Zzz' => self::getType('Zzz'),
            'TestInterface' => self::getType('TestInterface'),
            'TestUnion' => self::getType('TestUnion'),
            'UnionInvalidResolvedType' => self::getType('UnionInvalidResolvedType'),
            'CompositeInput' => self::getType('CompositeInput'),
            'SimpleInput' => self::getType('SimpleInput'),
            'DefaultsInput' => self::getType('DefaultsInput'),
            'SimpleEnum' => self::getType('SimpleEnum'),
            'ArrayEnum' => self::getType('ArrayEnum'),
            'DescriptionEnum' => self::getType('DescriptionEnum'),
            'TestScalar' => self::getType('TestScalar'),
            'ComplexDefaultsInput' => self::getType('ComplexDefaultsInput'),
            'NullFieldResolution' => self::getType('NullFieldResolution'),
            'NullListResolution' => self::getType('NullListResolution'),
            'SimpleType' => self::getType('SimpleType'),
            'InterfaceAbc' => self::getType('InterfaceAbc'),
            'InterfaceEfg' => self::getType('InterfaceEfg'),
            'FragmentTypeA' => self::getType('FragmentTypeA'),
            'FragmentTypeB' => self::getType('FragmentTypeB'),
            'SimpleEmptyTestInput' => self::getType('SimpleEmptyTestInput'),
            'InterfaceChildType' => self::getType('InterfaceChildType'),
        ], [
            'testDirective' => self::getType('testDirective'),
            'invalidDirectiveType' => self::getType('invalidDirectiveType'),
        ]);

        return self::$container;
    }

    public static function getQuery() : Type
    {
        if (self::$query !== null) {
            return self::$query;
        }

        return self::$query = new class extends Type
        {
            protected const NAME = 'Query';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'fieldAbc',
                        TestSchema::getTypeAbc(),
                        static function () : int {
                            return 1;
                        },
                    ),
                    new ResolvableField(
                        'fieldUnion',
                        TestSchema::getUnion(),
                        static function () {
                            return 1;
                        },
                    ),
                    new ResolvableField(
                        'fieldInvalidType',
                        TestSchema::getUnionInvalidResolvedType(),
                        static function () : string {
                            return 'invalidType';
                        },
                    ),
                    new ResolvableField(
                        'fieldThrow',
                        TestSchema::getTypeAbc(),
                        static function () : void {
                            throw new \Exception('Random exception');
                        },
                    ),
                    new ResolvableField(
                        'fieldList',
                        Container::String()->notNullList(),
                        static function () : array {
                            return ['testValue1', 'testValue2', 'testValue3'];
                        },
                    ),
                    new ResolvableField(
                        'fieldListList',
                        Container::String()->list()->list(),
                        static function () : array {
                            return [
                                ['testValue11', 'testValue12', 'testValue13'],
                                ['testValue21', 'testValue22'],
                                ['testValue31'],
                                null,
                            ];
                        },
                    ),
                    new ResolvableField(
                        'fieldListInt',
                        Container::Int()->notNullList(),
                        static function () : array {
                            return [1, 2, 3];
                        },
                    ),
                    new ResolvableField(
                        'fieldListFilter',
                        TestSchema::getTypeFilterData()->notNullList(),
                        static function () {
                            return [
                                (object) [
                                    'data' => (object) [
                                        'name' => 'testValue1',
                                        'rating' => 98,
                                        'coefficient' => 0.99,
                                        'listName' => [
                                            'testValue',
                                            '1',
                                        ],
                                        'isReady' => true,
                                    ],
                                ],
                                (object) [
                                    'data' => (object) [
                                        'name' => 'testValue2',
                                        'rating' => 99,
                                        'coefficient' => 1.00,
                                        'listName' => [
                                            'testValue',
                                            '2',
                                            'test1',
                                        ],
                                        'isReady' => false,
                                    ],
                                ],
                                (object) [
                                    'data' => (object) [
                                        'name' => 'testValue3',
                                        'rating' => 100,
                                        'coefficient' => 1.01,
                                        'listName' => [
                                            'testValue',
                                            '3',
                                            'test1',
                                            'test2',
                                        ],
                                        'isReady' => false,
                                    ],
                                ],
                                (object) [
                                    'data' => (object) [
                                        'name' => 'testValue4',
                                        'rating' => null,
                                        'coefficient' => null,
                                        'listName' => null,
                                        'isReady' => null,
                                    ],
                                ],
                            ];
                        },
                    ),
                    new ResolvableField(
                        'fieldListFloat',
                        Container::Float()->notNullList(),
                        static function () : array {
                            return [1.00, 1.01, 1.02];
                        },
                    ),
                    new ResolvableField(
                        'fieldObjectList',
                        TestSchema::getTypeXyz()->notNullList(),
                        static function () {
                            return [
                                (object) ['name' => 'testValue1'],
                                (object) ['name' => 'testValue2'],
                                (object) ['name' => 'testValue3'],
                            ];
                        },
                    ),
                    new ResolvableField(
                        'fieldAbstractList',
                        TestSchema::getUnion()->list(),
                        static function () : array {
                            return [
                                1,
                                1,
                                (object) ['name' => 'testName'],
                            ];
                        },
                    ),
                    new ResolvableField(
                        'fieldNull',
                        TestSchema::getNullFieldResolution(),
                        static function () : void {
                        },
                    ),
                    new ResolvableField(
                        'fieldNullList',
                        TestSchema::getNullListResolution(),
                        static function () : void {
                        },
                    ),
                    new ResolvableField(
                        'fieldAbstractNullList',
                        TestSchema::getUnion()->notNullList(),
                        static function () : array {
                            return [
                                1,
                                (object) ['name' => 'test'],
                                (object) ['name' => 'test'],
                                null,
                                (object) ['name' => 'test'],
                            ];
                        },
                    ),
                    ResolvableField::create(
                        'fieldArgumentDefaults',
                        TestSchema::getSimpleType()->notNull(),
                        static function ($parent, ?array $inputNumberList, ?bool $inputBool) {
                            return (object) ['number' => $inputNumberList, 'bool' => $inputBool];
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'inputNumberList',
                            Container::Int()->list(),
                        ),
                        new Argument(
                            'inputBool',
                            Container::Boolean(),
                        ),
                    ])),
                    new ResolvableField(
                        'fieldInvalidInput',
                        TestSchema::getSimpleType(),
                        static function () : array {
                            return [
                                'name' => 'testName',
                                'number' => 123,
                                'bool' => true,
                                'notDefinedField' => 'testValue',
                            ];
                        },
                    ),
                    new ResolvableField(
                        'fieldEmptyObject',
                        TestSchema::getSimpleEmptyTestInput(),
                        static function () : \stdClass {
                            return new \stdClass();
                        },
                    ),
                    new ResolvableField(
                        'fieldFragment',
                        TestSchema::getInterfaceAbc(),
                        static function () : \stdClass {
                            return new \stdClass();
                        },
                    ),
                    ResolvableField::create(
                        'fieldMerge',
                        TestSchema::getSimpleType()->notNull(),
                        static function ($parent, \stdClass $inputComplex) : \stdClass {
                            $return = new \stdClass();

                            $return->name = $inputComplex->innerObject->name
                                . ' ' . $inputComplex->innerObject->inner->name
                                . ' ' . $inputComplex->innerObject->innerNotNull->name
                                . ' ' . $inputComplex->innerListObjects[0]->name
                                . ' ' . $inputComplex->innerListObjects[0]->inner->name
                                . ' ' . $inputComplex->innerListObjects[1]->name;
                            $return->number = \array_merge(
                                $inputComplex->innerObject->innerNotNull->number,
                                $inputComplex->innerObject->inner->number,
                                $inputComplex->innerListObjects[0]->inner->number,
                                $inputComplex->innerListObjects[0]->innerNotNull->number,
                                $inputComplex->innerListObjects[1]->innerList[1]->number,
                            );
                            $return->bool = $inputComplex->innerObject->innerNotNull->bool;

                            return $return;
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'inputComplex',
                            TestSchema::getComplexDefaultsInput()->notNull(),
                        ),
                    ])),
                    ResolvableField::create(
                        'fieldRequiredArgumentInvalid',
                        TestSchema::getSimpleType(),
                        static function ($parent, $name) : void {
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'name',
                            Container::String()->notNull(),
                        ),
                    ])),
                    ResolvableField::create(
                        'fieldEnumArg',
                        TestSchema::getSimpleEnum()->notNull(),
                        static function ($parent, string $val) : string {
                            return $val;
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'val',
                            TestSchema::getSimpleEnum()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeAbc() : Type
    {
        return new class extends Type
        {
            protected const NAME = 'Abc';

            public function validateNonNullValue($rawValue) : bool
            {
                return $rawValue === 1;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    (new ResolvableField(
                        'fieldXyz',
                        TestSchema::getTypeXyz(),
                        static function (int $parent, ?int $arg1, ?\stdClass $arg2) {
                            $object = new \stdClass();

                            if ($arg2 === null) {
                                $object->name = 'Test ' . $arg1;
                            } else {
                                $concat = static function (\stdClass $objectVal) use (&$concat) : string {
                                    $str = '';

                                    foreach ($objectVal as $key => $item) {
                                        if ($item instanceof \stdClass) {
                                            $print = '{' . $concat($item) . '}';
                                        } elseif (\is_array($item)) {
                                            $print = '[]';
                                        } elseif (\is_scalar($item)) {
                                            $print = $item;
                                        } elseif ($item === null) {
                                            $print = 'null';
                                        } else {
                                            throw new \RuntimeException();
                                        }

                                        $str .= $key . ': ' . $print . '; ';
                                    }

                                    return $str;
                                };

                                $object->name = $concat($arg2);
                            }

                            return $object;
                        },
                    ))->setDeprecated()->setArguments(new ArgumentSet([
                        Argument::create('arg1', Container::Int())->setDefaultValue(123),
                        new Argument('arg2', TestSchema::getCompositeInput()),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeXyz() : Type
    {
        return new class extends Type
        {
            protected const NAME = 'Xyz';

            public function __construct()
            {
                parent::__construct(new InterfaceSet([TestSchema::getInterface()]));
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'name',
                        Container::String()->notNull(),
                        static function (\stdClass $parent) {
                            return $parent->name;
                        },
                    ),
                ]);
            }
        };
    }

    public static function getTypeZzz() : Type
    {
        return new class extends Type
        {
            protected const NAME = 'Zzz';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'enumList',
                        TestSchema::getSimpleEnum()->list(),
                        static function () {
                            return ['A', 'B'];
                        },
                    ),
                ]);
            }
        };
    }

    public static function getTypeFilterInner() : Type
    {
        return new class extends Type
        {
            protected const NAME = 'FilterInner';

            public function __construct()
            {
                parent::__construct(new InterfaceSet([TestSchema::getInterface()]));
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'name',
                        Container::String()->notNull(),
                        static function (\stdClass $parent) : string {
                            return $parent->name;
                        },
                    ),
                    new ResolvableField(
                        'listName',
                        Container::String()->list(),
                        static function (\stdClass $parent) : ?array {
                            return $parent->listName;
                        },
                    ),
                    new ResolvableField(
                        'rating',
                        Container::Int(),
                        static function (\stdClass $parent) : ?int {
                            return $parent->rating;
                        },
                    ),
                    new ResolvableField(
                        'coefficient',
                        Container::Float(),
                        static function (\stdClass $parent) : ?float {
                            return $parent->coefficient;
                        },
                    ),
                    new ResolvableField(
                        'isReady',
                        Container::Boolean(),
                        static function (\stdClass $parent) : ?bool {
                            return $parent->isReady;
                        },
                    ),
                ]);
            }
        };
    }

    public static function getTypeFilterData() : Type
    {
        return new class extends Type
        {
            protected const NAME = 'FilterData';

            public function __construct()
            {
                parent::__construct();
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'data',
                        TestSchema::getTypeFilterInner()->notNull(),
                        static function (\stdClass $parent) : \stdClass {
                            return $parent->data;
                        },
                    ),
                ]);
            }
        };
    }

    public static function getCompositeInput() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'CompositeInput';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument(
                        'name',
                        Container::String()->notNull(),
                    ),
                    new Argument(
                        'inner',
                        TestSchema::getSimpleInput(),
                    ),
                    new Argument(
                        'innerList',
                        TestSchema::getSimpleInput()->notNullList(),
                    ),
                    new Argument(
                        'innerNotNull',
                        TestSchema::getSimpleInput()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function getSimpleInput() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'SimpleInput';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument(
                        'name',
                        Container::String()->notNull(),
                    ),
                    new Argument(
                        'number',
                        Container::Int()->notNullList(),
                    ),
                    new Argument(
                        'bool',
                        Container::Boolean(),
                    ),
                ]);
            }
        };
    }

    public static function getDefaultsInput() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'DefaultsInput';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'scalar',
                        Container::String()->notNull(),
                    )->setDefaultValue('defaultString'),
                    Argument::create(
                        'enum',
                        TestSchema::getSimpleEnum()->notNull(),
                    )->setDefaultValue('A'),
                    Argument::create(
                        'list',
                        Container::String()->notNullList(),
                    )->setDefaultValue(['string1', 'string2']),
                    Argument::create(
                        'object',
                        TestSchema::getSimpleInput()->notNull(),
                    )->setDefaultValue((object) ['name' => 'string', 'number' => [1, 2]]),
                    Argument::create(
                        'listObjects',
                        TestSchema::getSimpleInput()->notNullList(),
                    )->setDefaultValue([(object) ['name' => 'string', 'number' => [1]], (object) ['name' => 'string', 'number' => []]]),
                ]);
            }
        };
    }

    public static function getInterface() : InterfaceType
    {
        return new class extends InterfaceType
        {
            protected const NAME = 'TestInterface';

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
                return new TypeIntermediateValue(TestSchema::getTypeXyz(), $rawValue);
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field('name', Container::String()->notNull()),
                ]);
            }
        };
    }

    public static function getInterfaceAbc() : InterfaceType
    {
        return new class extends InterfaceType
        {
            protected const NAME = 'InterfaceAbc';

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
                return new TypeIntermediateValue(TestSchema::getFragmentTypeA(), $rawValue);
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field(
                        'name',
                        Container::String()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function getInterfaceEfg() : InterfaceType
    {
        return new class extends InterfaceType
        {
            protected const NAME = 'InterfaceEfg';

            public function __construct()
            {
                parent::__construct(new InterfaceSet([
                    TestSchema::getInterfaceAbc(),
                ]));
            }

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
                return new TypeIntermediateValue(TestSchema::getFragmentTypeB(), $rawValue);
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field(
                        'number',
                        Container::Int(),
                    ),
                ]);
            }
        };
    }

    public static function getInterfaceChildType() : Type
    {
        return new class extends Type
        {
            protected const NAME = 'InterfaceChildType';

            public function __construct()
            {
                parent::__construct(new InterfaceSet([
                    TestSchema::getInterfaceAbc(),
                ]));
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'name',
                        Container::String()->notNull(),
                        static function (\stdClass $parent, string $argName) {
                            return $argName;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'argName',
                            Container::String(),
                        )->setDefaultValue('testValue'),
                    ])),
                ]);
            }
        };
    }

    public static function getFragmentTypeA() : Type
    {
        return new class extends Type
        {
            protected const NAME = 'FragmentTypeA';

            public function __construct()
            {
                parent::__construct(new InterfaceSet([
                    TestSchema::getInterfaceAbc(),
                ]));
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'name',
                        Container::String()->notNull(),
                        static function (\stdClass $parent, string $name = 'defaultA') {
                            return $parent->name
                                ?? $name;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'name',
                            Container::String()->notNull(),
                        )->setDefaultValue('defaultA'),
                    ])),
                ]);
            }
        };
    }

    public static function getFragmentTypeB() : Type
    {
        return new class extends Type
        {
            protected const NAME = 'FragmentTypeB';

            public function __construct()
            {
                parent::__construct(new InterfaceSet([
                    TestSchema::getInterfaceEfg(),
                ]));
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'name',
                        Container::String()->notNull(),
                        static function (\stdClass $parent, $name) {
                            return $parent->name
                                ?? $name;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'name',
                            Container::String()->notNull(),
                        )->setDefaultValue('defaultB'),
                    ])),
                    ResolvableField::create(
                        'number',
                        Container::Int(),
                        static function (\stdClass $parent, $number) {
                            return $parent->number
                                ?? $number;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'number',
                            Container::Int(),
                        )->setDefaultValue(5),
                    ])),
                    ResolvableField::create(
                        'bool',
                        Container::Boolean(),
                        static function (\stdClass $parent, $bool) {
                            return $parent->bool
                                ?? $bool;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'bool',
                            Container::Boolean(),
                        )->setDefaultValue(false),
                    ])),
                ]);
            }
        };
    }

    public static function getUnionInvalidResolvedType() : UnionType
    {
        return new class extends UnionType
        {
            protected const NAME = 'TestUnionInvalidResolvedType';

            public function __construct()
            {
                parent::__construct(new TypeSet([
                    TestSchema::getTypeAbc(),
                ]));
            }

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
                return new TypeIntermediateValue(TestSchema::getTypeZzz(), $rawValue);
            }
        };
    }

    public static function getUnion() : UnionType
    {
        return new class extends UnionType
        {
            protected const NAME = 'TestUnion';

            public function __construct()
            {
                parent::__construct(new TypeSet([
                    TestSchema::getTypeAbc(),
                    TestSchema::getTypeXyz(),
                ]));
            }

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
                if ($rawValue === 1) {
                    return new TypeIntermediateValue(TestSchema::getTypeAbc(), $rawValue);
                }

                return new TypeIntermediateValue(TestSchema::getTypeXyz(), $rawValue);
            }
        };
    }

    public static function getSimpleEnum() : EnumType
    {
        return new class extends EnumType
        {
            public const A = 'A';
            public const B = 'B';
            public const C = 'C';
            public const D = 'D';
            protected const NAME = 'SimpleEnum';

            public function __construct()
            {
                parent::__construct(self::fromConstants());
            }
        };
    }

    public static function getArrayEnum() : EnumType
    {
        return new class extends EnumType
        {
            /** First description */
            public const A = 'A';
            /** Second description */
            public const B = 'B';
            /** Third description */
            public const C = 'C';
            protected const NAME = 'ArrayEnum';

            public function __construct()
            {
                parent::__construct(self::fromConstants());
            }
        };
    }

    public static function getDescriptionEnum() : EnumType
    {
        return new class extends EnumType
        {
            protected const NAME = 'DescriptionEnum';

            public function __construct()
            {
                parent::__construct(new EnumItemSet([
                    new EnumItem('A', 'single line description'),
                    (new EnumItem('B'))
                        ->setDeprecated(),
                    new EnumItem('C', 'multi line' . \PHP_EOL . 'description'),
                    (new EnumItem('D', 'single line description'))
                        ->setDeprecated('reason'),
                ]));
            }
        };
    }

    public static function getTestScalar() : ScalarType
    {
        return new class extends ScalarType
        {
            protected const NAME = 'TestScalar';

            public function validateAndCoerceInput(mixed $rawValue) : mixed
            {
                return true;
            }

            public function coerceOutput(mixed $rawValue) : string|int|float|bool
            {
                return true;
            }
        };
    }

    public static function getTestDirective() : Directive
    {
        return new class extends Directive implements FieldLocation
        {
            protected const NAME = 'testDirective';
            protected const REPEATABLE = true;

            public static int $count = 0;

            public function validateFieldUsage(
                Field $field,
                ArgumentValueSet $arguments,
            ) : bool
            {
                return true;
            }

            public function resolveFieldBefore(ArgumentValueSet $arguments) : SelectionDirectiveResult
            {
                ++self::$count;

                return SelectionDirectiveResult::NONE;
            }

            public function resolveFieldAfter(
                ArgumentValueSet $arguments,
                FieldValue $fieldValue,
            ) : SelectionDirectiveResult
            {
                return SelectionDirectiveResult::NONE;
            }

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet();
            }
        };
    }

    public static function getInvalidDirectiveType() : Directive
    {
        return new class extends Directive implements FieldLocation
        {
            protected const NAME = 'invalidDirectiveType';

            public function validateFieldUsage(
                Field $field,
                ArgumentValueSet $arguments,
            ) : bool
            {
                return false;
            }

            public function resolveFieldBefore(ArgumentValueSet $arguments) : SelectionDirectiveResult
            {
                return SelectionDirectiveResult::NONE;
            }

            public function resolveFieldAfter(
                ArgumentValueSet $arguments,
                FieldValue $fieldValue,
            ) : SelectionDirectiveResult
            {
                return SelectionDirectiveResult::NONE;
            }

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet();
            }
        };
    }

    public static function getComplexDefaultsInput() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'ComplexDefaultsInput';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'innerObject',
                        TestSchema::getCompositeInput(),
                    )->setDefaultValue((object) [
                        'name' => 'testName',
                        'inner' => (object) ['name' => 'string', 'number' => [1, 2, 3]],
                        'innerList' => [
                            (object) ['name' => 'string', 'number' => [1]],
                            (object) ['name' => 'string', 'number' => [1, 2, 3, 4]],
                        ],
                        'innerNotNull' => (object) ['name' => 'string', 'number' => [1, 2]],
                    ]),
                    Argument::create(
                        'innerListObjects',
                        TestSchema::getCompositeInput()->list(),
                    )->setDefaultValue([
                        (object) [
                            'name' => 'testName',
                            'inner' => (object) ['name' => 'string', 'number' => [1, 2, 3]],
                            'innerList' => [
                                (object) ['name' => 'string', 'number' => [1]],
                                (object) ['name' => 'string', 'number' => [1, 2, 3, 4]],
                            ],
                            'innerNotNull' => (object) ['name' => 'string', 'number' => [1, 2]],
                        ],
                        (object) [
                            'name' => 'testName2',
                            'inner' => (object) ['name' => 'string2', 'number' => [11, 22, 33]],
                            'innerList' => [
                                (object) ['name' => 'string2', 'number' => [11]],
                                (object) ['name' => 'string2', 'number' => [11, 22, 33, 44]],
                            ],
                            'innerNotNull' => (object) ['name' => 'string2', 'number' => [11, 22]],
                        ],
                    ]),
                ]);
            }
        };
    }

    public static function getSimpleType() : Type
    {
        return new class extends Type
        {
            protected const NAME = 'SimpleType';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'fieldName',
                        Container::String()->notNull(),
                        static function ($parent, $name) {
                            return $parent->name
                                ?? $name;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'name',
                            Container::String()->notNull(),
                        )->setDefaultValue('testValue'),
                    ])),
                    ResolvableField::create(
                        'fieldNumber',
                        Container::Int()->notNullList(),
                        static function ($parent, $number) {
                            return $parent->number
                                ?? $number;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'number',
                            Container::Int()->notNullList(),
                        )->setDefaultValue([1, 2]),
                    ])),
                    ResolvableField::create(
                        'fieldBool',
                        Container::Boolean(),
                        static function ($parent, $bool) {
                            return $parent->bool
                                ?? $bool;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'bool',
                            Container::Boolean(),
                        )->setDefaultValue(true),
                    ])),
                ]);
            }
        };
    }

    public static function getSimpleEmptyTestInput() : Type
    {
        return new class extends Type
        {
            protected const NAME = 'SimpleEmptyTestInput';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'fieldNumber',
                        Container::Int(),
                        static function (\stdClass $parent) {
                            return $parent->number
                                ?? null;
                        },
                    ),
                ]);
            }
        };
    }

    public static function getNullFieldResolution() : Type
    {
        return new class extends Type {
            protected const NAME = 'NullFieldResolution';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'stringType',
                        Container::String()->notNull(),
                        static function ($parent, $string) {
                            return $string;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'nullString',
                            Container::String(),
                        )->setDefaultValue(null),
                    ])),
                    ResolvableField::create(
                        'interfaceType',
                        TestSchema::getInterface()->notNull(),
                        static function ($parent, $interface) {
                            return $interface;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'nullInterface',
                            Container::String(),
                        )->setDefaultValue(null),
                    ])),
                    ResolvableField::create(
                        'unionType',
                        TestSchema::getUnion()->notNull(),
                        static function ($parent, $union) {
                            return $union;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'nullUnion',
                            Container::String(),
                        )->setDefaultValue(null),
                    ])),
                ]);
            }
        };
    }

    public static function getNullListResolution() : Type
    {
        return new class extends Type {
            protected const NAME = 'NullListResolution';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'stringListType',
                        Container::String()->notNullList(),
                        static function ($parent, $string) {
                            return $string;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'nullString',
                            Container::String(),
                        )->setDefaultValue(null),
                    ])),
                    ResolvableField::create(
                        'interfaceListType',
                        TestSchema::getInterface()->notNullList(),
                        static function ($parent, $interface) {
                            return $interface;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'nullInterface',
                            Container::String()->list(),
                        )->setDefaultValue(null),
                    ])),
                    ResolvableField::create(
                        'unionListType',
                        TestSchema::getUnion()->notNullList(),
                        static function ($parent, $union) {
                            return $union;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'nullUnion',
                            Container::String()->list(),
                        )->setDefaultValue(null),
                    ])),
                ]);
            }
        };
    }
}
