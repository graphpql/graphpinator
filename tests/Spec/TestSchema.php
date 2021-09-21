<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class TestSchema
{
    use \Nette\StaticClass;

    private static array $types = [];
    private static ?\Graphpinator\Typesystem\Container $container = null;

    public static function getSchema() : \Graphpinator\Typesystem\Schema
    {
        return new \Graphpinator\Typesystem\Schema(
            self::getContainer(),
            self::getQuery(),
        );
    }

    public static function getFullSchema() : \Graphpinator\Typesystem\Schema
    {
        $query = self::getQuery();

        return new \Graphpinator\Typesystem\Schema(
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
            'invalidDirectiveResult' => self::getInvalidDirectiveResult(),
            'invalidDirectiveType' => self::getInvalidDirectiveType(),
        };

        return self::$types[$name];
    }

    public static function getContainer() : \Graphpinator\Typesystem\Container
    {
        if (self::$container !== null) {
            return self::$container;
        }

        self::$container = new \Graphpinator\SimpleContainer([
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
            'invalidDirectiveResult' => self::getType('invalidDirectiveResult'),
            'invalidDirectiveType' => self::getType('invalidDirectiveType'),
        ]);

        return self::$container;
    }

    public static function getQuery() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'Query';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'fieldAbc',
                        TestSchema::getTypeAbc(),
                        static function () : int {
                            return 1;
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'fieldUnion',
                        TestSchema::getUnion(),
                        static function () {
                            return 1;
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'fieldInvalidType',
                        TestSchema::getUnionInvalidResolvedType(),
                        static function () : string {
                            return 'invalidType';
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'fieldThrow',
                        TestSchema::getTypeAbc(),
                        static function () : void {
                            throw new \Exception('Random exception');
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'fieldList',
                        \Graphpinator\Typesystem\Container::String()->notNullList(),
                        static function () : array {
                            return ['testValue1', 'testValue2', 'testValue3'];
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'fieldListList',
                        \Graphpinator\Typesystem\Container::String()->list()->list(),
                        static function () : array {
                            return [
                                ['testValue11', 'testValue12', 'testValue13'],
                                ['testValue21', 'testValue22'],
                                ['testValue31'],
                                null,
                            ];
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'fieldListInt',
                        \Graphpinator\Typesystem\Container::Int()->notNullList(),
                        static function () : array {
                            return [1, 2, 3];
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
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
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'fieldListFloat',
                        \Graphpinator\Typesystem\Container::Float()->notNullList(),
                        static function () : array {
                            return [1.00, 1.01, 1.02];
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
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
                    new \Graphpinator\Typesystem\Field\ResolvableField(
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
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'fieldNull',
                        TestSchema::getNullFieldResolution(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'fieldNullList',
                        TestSchema::getNullListResolution(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
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
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'fieldArgumentDefaults',
                        TestSchema::getSimpleType()->notNull(),
                        static function ($parent, ?array $inputNumberList, ?bool $inputBool) {
                            return (object) ['number' => $inputNumberList, 'bool' => $inputBool];
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        new \Graphpinator\Typesystem\Argument\Argument(
                            'inputNumberList',
                            \Graphpinator\Typesystem\Container::Int()->list(),
                        ),
                        new \Graphpinator\Typesystem\Argument\Argument(
                            'inputBool',
                            \Graphpinator\Typesystem\Container::Boolean(),
                        ),
                    ])),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
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
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'fieldEmptyObject',
                        TestSchema::getSimpleEmptyTestInput(),
                        static function () : \stdClass {
                            return new \stdClass();
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'fieldFragment',
                        TestSchema::getInterfaceAbc(),
                        static function () : \stdClass {
                            return new \stdClass();
                        },
                    ),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
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
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        new \Graphpinator\Typesystem\Argument\Argument(
                            'inputComplex',
                            TestSchema::getComplexDefaultsInput()->notNull(),
                        ),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'fieldRequiredArgumentInvalid',
                        TestSchema::getSimpleType(),
                        static function ($parent, $name) : void {
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        new \Graphpinator\Typesystem\Argument\Argument(
                            'name',
                            \Graphpinator\Typesystem\Container::String()->notNull(),
                        ),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'fieldEnumArg',
                        TestSchema::getSimpleEnum()->notNull(),
                        static function ($parent, string $val) : string {
                            return $val;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        new \Graphpinator\Typesystem\Argument\Argument(
                            'val',
                            TestSchema::getSimpleEnum()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeAbc() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'Abc';
            protected const DESCRIPTION = 'Test Abc description';

            public function validateNonNullValue($rawValue) : bool
            {
                return $rawValue === 1;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    (new \Graphpinator\Typesystem\Field\ResolvableField(
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
                    ))->setDeprecated()->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create('arg1', \Graphpinator\Typesystem\Container::Int())->setDefaultValue(123),
                        new \Graphpinator\Typesystem\Argument\Argument('arg2', TestSchema::getCompositeInput()),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeXyz() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'Xyz';
            protected const DESCRIPTION = null;

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Typesystem\InterfaceSet([TestSchema::getInterface()]));
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'name',
                        \Graphpinator\Typesystem\Container::String()->notNull(),
                        static function (\stdClass $parent) {
                            return $parent->name;
                        },
                    ),
                ]);
            }
        };
    }

    public static function getTypeZzz() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'Zzz';
            protected const DESCRIPTION = null;

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    new \Graphpinator\Typesystem\Field\ResolvableField(
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

    public static function getTypeFilterInner() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'FilterInner';
            protected const DESCRIPTION = null;

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Typesystem\InterfaceSet([TestSchema::getInterface()]));
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'name',
                        \Graphpinator\Typesystem\Container::String()->notNull(),
                        static function (\stdClass $parent) : string {
                            return $parent->name;
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'listName',
                        \Graphpinator\Typesystem\Container::String()->list(),
                        static function (\stdClass $parent) : ?array {
                            return $parent->listName;
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'rating',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function (\stdClass $parent) : ?int {
                            return $parent->rating;
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'coefficient',
                        \Graphpinator\Typesystem\Container::Float(),
                        static function (\stdClass $parent) : ?float {
                            return $parent->coefficient;
                        },
                    ),
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'isReady',
                        \Graphpinator\Typesystem\Container::Boolean(),
                        static function (\stdClass $parent) : ?bool {
                            return $parent->isReady;
                        },
                    ),
                ]);
            }
        };
    }

    public static function getTypeFilterData() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'FilterData';
            protected const DESCRIPTION = null;

            public function __construct()
            {
                parent::__construct();
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    new \Graphpinator\Typesystem\Field\ResolvableField(
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

    public static function getCompositeInput() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'CompositeInput';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    new \Graphpinator\Typesystem\Argument\Argument(
                        'name',
                        \Graphpinator\Typesystem\Container::String()->notNull(),
                    ),
                    new \Graphpinator\Typesystem\Argument\Argument(
                        'inner',
                        TestSchema::getSimpleInput(),
                    ),
                    new \Graphpinator\Typesystem\Argument\Argument(
                        'innerList',
                        TestSchema::getSimpleInput()->notNullList(),
                    ),
                    new \Graphpinator\Typesystem\Argument\Argument(
                        'innerNotNull',
                        TestSchema::getSimpleInput()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function getSimpleInput() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'SimpleInput';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    new \Graphpinator\Typesystem\Argument\Argument(
                        'name',
                        \Graphpinator\Typesystem\Container::String()->notNull(),
                    ),
                    new \Graphpinator\Typesystem\Argument\Argument(
                        'number',
                        \Graphpinator\Typesystem\Container::Int()->notNullList(),
                    ),
                    new \Graphpinator\Typesystem\Argument\Argument(
                        'bool',
                        \Graphpinator\Typesystem\Container::Boolean(),
                    ),
                ]);
            }
        };
    }

    public static function getDefaultsInput() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'DefaultsInput';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'scalar',
                        \Graphpinator\Typesystem\Container::String()->notNull(),
                    )->setDefaultValue('defaultString'),
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'enum',
                        TestSchema::getSimpleEnum()->notNull(),
                    )->setDefaultValue('A'),
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'list',
                        \Graphpinator\Typesystem\Container::String()->notNullList(),
                    )->setDefaultValue(['string1', 'string2']),
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'object',
                        TestSchema::getSimpleInput()->notNull(),
                    )->setDefaultValue((object) ['name' => 'string', 'number' => [1, 2]]),
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'listObjects',
                        TestSchema::getSimpleInput()->notNullList(),
                    )->setDefaultValue([(object) ['name' => 'string', 'number' => [1]], (object) ['name' => 'string', 'number' => []]]),
                ]);
            }
        };
    }

    public static function getInterface() : \Graphpinator\Typesystem\InterfaceType
    {
        return new class extends \Graphpinator\Typesystem\InterfaceType
        {
            protected const NAME = 'TestInterface';
            protected const DESCRIPTION = 'TestInterface Description';

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(TestSchema::getTypeXyz(), $rawValue);
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\FieldSet
            {
                return new \Graphpinator\Typesystem\Field\FieldSet([
                    new \Graphpinator\Typesystem\Field\Field('name', \Graphpinator\Typesystem\Container::String()->notNull()),
                ]);
            }
        };
    }

    public static function getInterfaceAbc() : \Graphpinator\Typesystem\InterfaceType
    {
        return new class extends \Graphpinator\Typesystem\InterfaceType
        {
            protected const NAME = 'InterfaceAbc';
            protected const DESCRIPTION = 'Interface Abc Description';

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(TestSchema::getFragmentTypeA(), $rawValue);
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\FieldSet
            {
                return new \Graphpinator\Typesystem\Field\FieldSet([
                    new \Graphpinator\Typesystem\Field\Field(
                        'name',
                        \Graphpinator\Typesystem\Container::String()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function getInterfaceEfg() : \Graphpinator\Typesystem\InterfaceType
    {
        return new class extends \Graphpinator\Typesystem\InterfaceType
        {
            protected const NAME = 'InterfaceEfg';
            protected const DESCRIPTION = 'Interface Efg Description';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Typesystem\InterfaceSet([
                    TestSchema::getInterfaceAbc(),
                ]));
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(TestSchema::getFragmentTypeB(), $rawValue);
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\FieldSet
            {
                return new \Graphpinator\Typesystem\Field\FieldSet([
                    new \Graphpinator\Typesystem\Field\Field(
                        'number',
                        \Graphpinator\Typesystem\Container::Int(),
                    ),
                ]);
            }
        };
    }

    public static function getInterfaceChildType() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'InterfaceChildType';
            protected const DESCRIPTION = null;

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Typesystem\InterfaceSet([
                    TestSchema::getInterfaceAbc(),
                ]));
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'name',
                        \Graphpinator\Typesystem\Container::String()->notNull(),
                        static function (\stdClass $parent, string $argName) {
                            return $argName;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'argName',
                            \Graphpinator\Typesystem\Container::String(),
                        )->setDefaultValue('testValue'),
                    ])),
                ]);
            }
        };
    }

    public static function getFragmentTypeA() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'FragmentTypeA';
            protected const DESCRIPTION = null;

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Typesystem\InterfaceSet([
                    TestSchema::getInterfaceAbc(),
                ]));
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'name',
                        \Graphpinator\Typesystem\Container::String()->notNull(),
                        static function (\stdClass $parent, string $name = 'defaultA') {
                            return $parent->name
                                ?? $name;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'name',
                            \Graphpinator\Typesystem\Container::String()->notNull(),
                        )->setDefaultValue('defaultA'),
                    ])),
                ]);
            }
        };
    }

    public static function getFragmentTypeB() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'FragmentTypeB';
            protected const DESCRIPTION = null;

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Typesystem\InterfaceSet([
                    TestSchema::getInterfaceEfg(),
                ]));
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'name',
                        \Graphpinator\Typesystem\Container::String()->notNull(),
                        static function (\stdClass $parent, $name) {
                            return $parent->name
                                ?? $name;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'name',
                            \Graphpinator\Typesystem\Container::String()->notNull(),
                        )->setDefaultValue('defaultB'),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'number',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function (\stdClass $parent, $number) {
                            return $parent->number
                                ?? $number;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'number',
                            \Graphpinator\Typesystem\Container::Int(),
                        )->setDefaultValue(5),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'bool',
                        \Graphpinator\Typesystem\Container::Boolean(),
                        static function (\stdClass $parent, $bool) {
                            return $parent->bool
                                ?? $bool;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'bool',
                            \Graphpinator\Typesystem\Container::Boolean(),
                        )->setDefaultValue(false),
                    ])),
                ]);
            }
        };
    }

    public static function getUnionInvalidResolvedType() : \Graphpinator\Typesystem\UnionType
    {
        return new class extends \Graphpinator\Typesystem\UnionType
        {
            protected const NAME = 'TestUnionInvalidResolvedType';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Typesystem\TypeSet([
                    TestSchema::getTypeAbc(),
                ]));
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(TestSchema::getTypeZzz(), $rawValue);
            }
        };
    }

    public static function getUnion() : \Graphpinator\Typesystem\UnionType
    {
        return new class extends \Graphpinator\Typesystem\UnionType
        {
            protected const NAME = 'TestUnion';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Typesystem\TypeSet([
                    TestSchema::getTypeAbc(),
                    TestSchema::getTypeXyz(),
                ]));
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                if ($rawValue === 1) {
                    return new \Graphpinator\Value\TypeIntermediateValue(TestSchema::getTypeAbc(), $rawValue);
                }

                return new \Graphpinator\Value\TypeIntermediateValue(TestSchema::getTypeXyz(), $rawValue);
            }
        };
    }

    public static function getSimpleEnum() : \Graphpinator\Typesystem\EnumType
    {
        return new class extends \Graphpinator\Typesystem\EnumType
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

    public static function getArrayEnum() : \Graphpinator\Typesystem\EnumType
    {
        return new class extends \Graphpinator\Typesystem\EnumType
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

    public static function getDescriptionEnum() : \Graphpinator\Typesystem\EnumType
    {
        return new class extends \Graphpinator\Typesystem\EnumType
        {
            protected const NAME = 'DescriptionEnum';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Typesystem\EnumItem\EnumItemSet([
                    new \Graphpinator\Typesystem\EnumItem\EnumItem('A', 'single line description'),
                    (new \Graphpinator\Typesystem\EnumItem\EnumItem('B'))
                        ->setDeprecated(),
                    new \Graphpinator\Typesystem\EnumItem\EnumItem('C', 'multi line' . \PHP_EOL . 'description'),
                    (new \Graphpinator\Typesystem\EnumItem\EnumItem('D', 'single line description'))
                        ->setDeprecated('reason'),
                ]));
            }
        };
    }

    public static function getTestScalar() : \Graphpinator\Typesystem\ScalarType
    {
        return new class extends \Graphpinator\Typesystem\ScalarType
        {
            protected const NAME = 'TestScalar';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTestDirective() : \Graphpinator\Typesystem\Directive
    {
        return new class extends \Graphpinator\Typesystem\Directive implements \Graphpinator\Typesystem\Location\FieldLocation
        {
            protected const NAME = 'testDirective';
            protected const REPEATABLE = true;
            public static int $count = 0;

            public function validateFieldUsage(
                \Graphpinator\Typesystem\Field\Field $field,
                \Graphpinator\Value\ArgumentValueSet $arguments,
            ) : bool
            {
                return true;
            }

            public function resolveFieldBefore(\Graphpinator\Value\ArgumentValueSet $arguments) : string
            {
                ++self::$count;

                return \Graphpinator\Typesystem\Location\FieldLocation::NONE;
            }

            public function resolveFieldAfter(
                \Graphpinator\Value\ArgumentValueSet $arguments,
                \Graphpinator\Value\FieldValue $fieldValue,
            ) : string
            {
                return \Graphpinator\Typesystem\Location\FieldLocation::NONE;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet();
            }
        };
    }

    public static function getInvalidDirectiveResult() : \Graphpinator\Typesystem\Directive
    {
        return new class extends \Graphpinator\Typesystem\Directive implements \Graphpinator\Typesystem\Location\FieldLocation
        {
            protected const NAME = 'invalidDirectiveResult';
            protected const REPEATABLE = true;

            public function validateFieldUsage(
                \Graphpinator\Typesystem\Field\Field $field,
                \Graphpinator\Value\ArgumentValueSet $arguments,
            ) : bool
            {
                return true;
            }

            public function resolveFieldBefore(\Graphpinator\Value\ArgumentValueSet $arguments) : string
            {
                return 'random';
            }

            public function resolveFieldAfter(
                \Graphpinator\Value\ArgumentValueSet $arguments,
                \Graphpinator\Value\FieldValue $fieldValue,
            ) : string
            {
                return \Graphpinator\Directive\FieldDirectiveResult::NONE;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet();
            }
        };
    }

    public static function getInvalidDirectiveType() : \Graphpinator\Typesystem\Directive
    {
        return new class extends \Graphpinator\Typesystem\Directive implements \Graphpinator\Typesystem\Location\FieldLocation
        {
            protected const NAME = 'invalidDirectiveType';

            public function validateFieldUsage(
                \Graphpinator\Typesystem\Field\Field $field,
                \Graphpinator\Value\ArgumentValueSet $arguments,
            ) : bool
            {
                return false;
            }

            public function resolveFieldBefore(\Graphpinator\Value\ArgumentValueSet $arguments) : string
            {
                return \Graphpinator\Directive\FieldDirectiveResult::NONE;
            }

            public function resolveFieldAfter(
                \Graphpinator\Value\ArgumentValueSet $arguments,
                \Graphpinator\Value\FieldValue $fieldValue,
            ) : string
            {
                return \Graphpinator\Directive\FieldDirectiveResult::NONE;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet();
            }
        };
    }

    public static function getComplexDefaultsInput() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'ComplexDefaultsInput';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
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
                    \Graphpinator\Typesystem\Argument\Argument::create(
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

    public static function getSimpleType() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'SimpleType';
            protected const DESCRIPTION = 'Simple desc';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'fieldName',
                        \Graphpinator\Typesystem\Container::String()->notNull(),
                        static function ($parent, $name) {
                            return $parent->name
                                ?? $name;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'name',
                            \Graphpinator\Typesystem\Container::String()->notNull(),
                        )->setDefaultValue('testValue'),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'fieldNumber',
                        \Graphpinator\Typesystem\Container::Int()->notNullList(),
                        static function ($parent, $number) {
                            return $parent->number
                                ?? $number;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'number',
                            \Graphpinator\Typesystem\Container::Int()->notNullList(),
                        )->setDefaultValue([1, 2]),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'fieldBool',
                        \Graphpinator\Typesystem\Container::Boolean(),
                        static function ($parent, $bool) {
                            return $parent->bool
                                ?? $bool;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'bool',
                            \Graphpinator\Typesystem\Container::Boolean(),
                        )->setDefaultValue(true),
                    ])),
                ]);
            }
        };
    }

    public static function getSimpleEmptyTestInput() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'SimpleEmptyTestInput';
            protected const DESCRIPTION = null;

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'fieldNumber',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function (\stdClass $parent) {
                            return $parent->number
                                ?? null;
                        },
                    ),
                ]);
            }
        };
    }

    public static function getNullFieldResolution() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'NullFieldResolution';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'stringType',
                        \Graphpinator\Typesystem\Container::String()->notNull(),
                        static function ($parent, $string) {
                            return $string;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'nullString',
                            \Graphpinator\Typesystem\Container::String(),
                        )->setDefaultValue(null),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'interfaceType',
                        TestSchema::getInterface()->notNull(),
                        static function ($parent, $interface) {
                            return $interface;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'nullInterface',
                            \Graphpinator\Typesystem\Container::String(),
                        )->setDefaultValue(null),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'unionType',
                        TestSchema::getUnion()->notNull(),
                        static function ($parent, $union) {
                            return $union;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'nullUnion',
                            \Graphpinator\Typesystem\Container::String(),
                        )->setDefaultValue(null),
                    ])),
                ]);
            }
        };
    }

    public static function getNullListResolution() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'NullListResolution';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'stringListType',
                        \Graphpinator\Typesystem\Container::String()->notNullList(),
                        static function ($parent, $string) {
                            return $string;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'nullString',
                            \Graphpinator\Typesystem\Container::String(),
                        )->setDefaultValue(null),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'interfaceListType',
                        TestSchema::getInterface()->notNullList(),
                        static function ($parent, $interface) {
                            return $interface;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'nullInterface',
                            TestSchema::getInterface()->list(),
                        )->setDefaultValue(null),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'unionListType',
                        TestSchema::getUnion()->notNullList(),
                        static function ($parent, $union) {
                            return $union;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'nullUnion',
                            TestSchema::getUnion()->list(),
                        )->setDefaultValue(null),
                    ])),
                ]);
            }
        };
    }
}
