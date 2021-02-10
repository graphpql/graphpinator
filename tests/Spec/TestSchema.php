<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class TestSchema
{
    use \Nette\StaticClass;

    private static array $types = [];
    private static ?\Graphpinator\Container\Container $container = null;

    public static function getSchema() : \Graphpinator\Type\Schema
    {
        return new \Graphpinator\Type\Schema(
            self::getContainer(),
            self::getQuery(),
        );
    }

    public static function getFullSchema() : \Graphpinator\Type\Schema
    {
        return new \Graphpinator\Type\Schema(
            self::getContainer(),
            self::getQuery(),
            self::getQuery(),
            self::getQuery(),
        );
    }

    public static function getType(string $name) : object
    {
        if (\array_key_exists($name, self::$types)) {
            return self::$types[$name];
        }

        self::$types[$name] = match($name) {
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
            'UploadType' => self::getUploadType(),
            'UploadInput' => self::getUploadInput(),
            'Upload' => new \Graphpinator\Module\Upload\UploadType(),
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

    public static function getContainer() : \Graphpinator\Container\Container
    {
        if (self::$container !== null) {
            return self::$container;
        }

        self::$container = new \Graphpinator\Container\SimpleContainer([
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
            'UploadType' => self::getType('UploadType'),
            'UploadInput' => self::getType('UploadInput'),
            'Upload' => self::getType('Upload'),
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

    public static function getQuery() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'Query';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'fieldAbc',
                        TestSchema::getTypeAbc(),
                        static function () : int {
                            return 1;
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldUnion',
                        TestSchema::getUnion(),
                        static function () {
                            return 1;
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldInvalidType',
                        TestSchema::getUnionInvalidResolvedType(),
                        static function () : string {
                            return 'invalidType';
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldThrow',
                        TestSchema::getTypeAbc(),
                        static function () : void {
                            throw new \Exception('Random exception');
                        },
                    ),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldUpload',
                        TestSchema::getUploadType()->notNull(),
                        static function ($parent, ?\Psr\Http\Message\UploadedFileInterface $file) : \Psr\Http\Message\UploadedFileInterface {
                            return $file;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'file',
                            new \Graphpinator\Module\Upload\UploadType(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldMultiUpload',
                        TestSchema::getUploadType()->notNullList(),
                        static function ($parent, array $files) : array {
                            return $files;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'files',
                            (new \Graphpinator\Module\Upload\UploadType())->list(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldInputUpload',
                        TestSchema::getUploadType()->notNull(),
                        static function ($parent, \stdClass $fileInput) : \Psr\Http\Message\UploadedFileInterface {
                            return $fileInput->file;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'fileInput',
                            TestSchema::getUploadInput()->notNull(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldInputMultiUpload',
                        TestSchema::getUploadType()->notNullList(),
                        static function ($parent, \stdClass $fileInput) : array {
                            return $fileInput->files;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'fileInput',
                            TestSchema::getUploadInput()->notNull(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldMultiInputUpload',
                        TestSchema::getUploadType()->notNullList(),
                        static function ($parent, array $fileInputs) {
                            $return = [];

                            foreach ($fileInputs as $fileInput) {
                                $return[] = $fileInput->file;
                            }

                            return $return;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'fileInputs',
                            TestSchema::getUploadInput()->notNullList(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldMultiInputMultiUpload',
                        TestSchema::getUploadType()->notNullList(),
                        static function ($parent, array $fileInputs) {
                            $return = [];

                            foreach ($fileInputs as $fileInput) {
                                $return += $fileInput->files;
                            }

                            return $return;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'fileInputs',
                            TestSchema::getUploadInput()->notNullList(),
                        ),
                    ])),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldList',
                        \Graphpinator\Container\Container::String()->notNullList(),
                        static function () : array {
                            return ['testValue1', 'testValue2', 'testValue3'];
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldListList',
                        \Graphpinator\Container\Container::String()->list()->list(),
                        static function () : array {
                            return [
                                ['testValue11', 'testValue12', 'testValue13'],
                                ['testValue21', 'testValue22'],
                                ['testValue31'],
                                null,
                            ];
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldListInt',
                        \Graphpinator\Container\Container::Int()->notNullList(),
                        static function () : array {
                            return [1, 2, 3];
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldListFilter',
                        TestSchema::getTypeFilterData()->notNullList(),
                        static function () {
                            return [
                                (object) ['data' => (object) [
                                    'name' => 'testValue1', 'rating' => 98, 'coefficient' => 0.99, 'listName' => ['testValue', '1'], 'isReady' => true,
                                ]],
                                (object) ['data' => (object) [
                                    'name' => 'testValue2', 'rating' => 99, 'coefficient' => 1.00, 'listName' => ['testValue', '2', 'test1'], 'isReady' => false,
                                ]],
                                (object) ['data' => (object) [
                                    'name' => 'testValue3', 'rating' => 100, 'coefficient' => 1.01, 'listName' => ['testValue', '3', 'test1', 'test2'], 'isReady' => false,
                                ]],
                                (object) ['data' => (object) [
                                    'name' => 'testValue4', 'rating' => null, 'coefficient' => null, 'listName' => null, 'isReady' => null,
                                ]],
                            ];
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldListFloat',
                        \Graphpinator\Container\Container::Float()->notNullList(),
                        static function () : array {
                            return [1.00, 1.01, 1.02];
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
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
                    new \Graphpinator\Field\ResolvableField(
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
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNull',
                        TestSchema::getNullFieldResolution(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNullList',
                        TestSchema::getNullListResolution(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
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
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldArgumentDefaults',
                        TestSchema::getSimpleType()->notNull(),
                        static function ($parent, ?array $inputNumberList, ?bool $inputBool) {
                            return (object) ['number' => $inputNumberList, 'bool' => $inputBool];
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'inputNumberList',
                            \Graphpinator\Container\Container::Int()->list(),
                        ),
                        new \Graphpinator\Argument\Argument(
                            'inputBool',
                            \Graphpinator\Container\Container::Boolean(),
                        ),
                    ])),
                    new \Graphpinator\Field\ResolvableField(
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
                    new \Graphpinator\Field\ResolvableField(
                        'fieldEmptyObject',
                        TestSchema::getSimpleEmptyTestInput(),
                        static function () : \stdClass {
                            return new \stdClass();
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldFragment',
                        TestSchema::getInterfaceAbc(),
                        static function () : \stdClass {
                            return new \stdClass();
                        },
                    ),
                    \Graphpinator\Field\ResolvableField::create(
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
                            $return->bool = $inputComplex->innerObject->inner->bool;

                            return $return;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'inputComplex',
                            TestSchema::getComplexDefaultsInput()->notNull(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldRequiredArgumentInvalid',
                        TestSchema::getSimpleType(),
                        static function ($parent, $name) : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'name',
                            \Graphpinator\Container\Container::String()->notNull(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldEnumArg',
                        TestSchema::getSimpleEnum()->notNull(),
                        static function ($parent, string $val) : string {
                            return $val;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'val',
                            TestSchema::getSimpleEnum()->notNull(),
                        ),
                    ])),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTypeAbc() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'Abc';
            protected const DESCRIPTION = 'Test Abc description';

            public function validateNonNullValue($rawValue) : bool
            {
                return $rawValue === 1;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    (new \Graphpinator\Field\ResolvableField(
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
                    ))->setDeprecated()->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create('arg1', \Graphpinator\Container\Container::Int())->setDefaultValue(123),
                        new \Graphpinator\Argument\Argument('arg2', TestSchema::getCompositeInput()),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeXyz() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'Xyz';
            protected const DESCRIPTION = null;

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Utils\InterfaceSet([TestSchema::getInterface()]));
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'name',
                        \Graphpinator\Container\Container::String()->notNull(),
                        static function (\stdClass $parent) {
                            return $parent->name;
                        },
                    ),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTypeZzz() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'Zzz';
            protected const DESCRIPTION = null;

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'enumList',
                        TestSchema::getSimpleEnum()->list(),
                        static function () {
                            return ['A', 'B'];
                        },
                    ),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTypeFilterInner() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'FilterInner';
            protected const DESCRIPTION = null;

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Utils\InterfaceSet([TestSchema::getInterface()]));
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'name',
                        \Graphpinator\Container\Container::String()->notNull(),
                        static function (\stdClass $parent) : string {
                            return $parent->name;
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'listName',
                        \Graphpinator\Container\Container::String()->list(),
                        static function (\stdClass $parent) : ?array {
                            return $parent->listName;
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'rating',
                        \Graphpinator\Container\Container::Int(),
                        static function (\stdClass $parent) : ?int {
                            return $parent->rating;
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'coefficient',
                        \Graphpinator\Container\Container::Float(),
                        static function (\stdClass $parent) : ?float {
                            return $parent->coefficient;
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'isReady',
                        \Graphpinator\Container\Container::Boolean(),
                        static function (\stdClass $parent) : ?bool {
                            return $parent->isReady;
                        },
                    ),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTypeFilterData() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'FilterData';
            protected const DESCRIPTION = null;

            public function __construct()
            {
                parent::__construct();
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'data',
                        TestSchema::getTypeFilterInner()->notNull(),
                        static function (\stdClass $parent) : \stdClass {
                            return $parent->data;
                        },
                    ),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getCompositeInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'CompositeInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'name',
                        \Graphpinator\Container\Container::String()->notNull(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'inner',
                        TestSchema::getSimpleInput(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'innerList',
                        TestSchema::getSimpleInput()->notNullList(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'innerNotNull',
                        TestSchema::getSimpleInput()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function getSimpleInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'SimpleInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'name',
                        \Graphpinator\Container\Container::String()->notNull(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'number',
                        \Graphpinator\Container\Container::Int()->notNullList(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'bool',
                        \Graphpinator\Container\Container::Boolean(),
                    ),
                ]);
            }
        };
    }

    public static function getDefaultsInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'DefaultsInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'scalar',
                        \Graphpinator\Container\Container::String()->notNull(),
                    )->setDefaultValue('defaultString'),
                    \Graphpinator\Argument\Argument::create(
                        'enum',
                        TestSchema::getSimpleEnum()->notNull(),
                    )->setDefaultValue('A'),
                    \Graphpinator\Argument\Argument::create(
                        'list',
                        \Graphpinator\Container\Container::String()->notNullList(),
                    )->setDefaultValue(['string1', 'string2']),
                    \Graphpinator\Argument\Argument::create(
                        'object',
                        TestSchema::getSimpleInput()->notNull(),
                    )->setDefaultValue((object) ['name' => 'string', 'number' => [1, 2]]),
                    \Graphpinator\Argument\Argument::create(
                        'listObjects',
                        TestSchema::getSimpleInput()->notNullList(),
                    )->setDefaultValue([(object) ['name' => 'string', 'number' => [1]], (object) ['name' => 'string', 'number' => []]]),
                ]);
            }
        };
    }

    public static function getInterface() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType
        {
            protected const NAME = 'TestInterface';
            protected const DESCRIPTION = 'TestInterface Description';

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(TestSchema::getTypeXyz(), $rawValue);
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field('name', \Graphpinator\Container\Container::String()->notNull()),
                ]);
            }
        };
    }

    public static function getInterfaceAbc() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType
        {
            protected const NAME = 'InterfaceAbc';
            protected const DESCRIPTION = 'Interface Abc Description';

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(TestSchema::getFragmentTypeA(), $rawValue);
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field(
                        'name',
                        \Graphpinator\Container\Container::String()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function getInterfaceEfg() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType
        {
            protected const NAME = 'InterfaceEfg';
            protected const DESCRIPTION = 'Interface Efg Description';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Utils\InterfaceSet([
                    TestSchema::getInterfaceAbc(),
                ]));
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(TestSchema::getFragmentTypeB(), $rawValue);
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field(
                        'number',
                        \Graphpinator\Container\Container::Int(),
                    ),
                ]);
            }
        };
    }

    public static function getInterfaceChildType() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'InterfaceChildType';
            protected const DESCRIPTION = null;

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Utils\InterfaceSet([
                    TestSchema::getInterfaceAbc(),
                ]));
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    \Graphpinator\Field\ResolvableField::create(
                        'name',
                        \Graphpinator\Container\Container::String()->notNull(),
                        static function (\stdClass $parent, string $argName) {
                            return $argName;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'argName',
                            \Graphpinator\Container\Container::String(),
                        )->setDefaultValue('testValue'),
                    ])),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getFragmentTypeA() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'FragmentTypeA';
            protected const DESCRIPTION = null;

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Utils\InterfaceSet([
                    TestSchema::getInterfaceAbc(),
                ]));
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    \Graphpinator\Field\ResolvableField::create(
                        'name',
                        \Graphpinator\Container\Container::String()->notNull(),
                        static function (\stdClass $parent, string $name = 'defaultA') {
                            return $parent->name
                                ?? $name;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'name',
                            \Graphpinator\Container\Container::String()->notNull(),
                        )->setDefaultValue('defaultA'),
                    ])),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getFragmentTypeB() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'FragmentTypeB';
            protected const DESCRIPTION = null;

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Utils\InterfaceSet([
                    TestSchema::getInterfaceEfg(),
                ]));
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    \Graphpinator\Field\ResolvableField::create(
                        'name',
                        \Graphpinator\Container\Container::String()->notNull(),
                        static function (\stdClass $parent, $name) {
                            return $parent->name
                                ?? $name;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'name',
                            \Graphpinator\Container\Container::String()->notNull(),
                        )->setDefaultValue('defaultB'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'number',
                        \Graphpinator\Container\Container::Int(),
                        static function (\stdClass $parent, $number) {
                            return $parent->number
                                ?? $number;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'number',
                            \Graphpinator\Container\Container::Int(),
                        )->setDefaultValue(5),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'bool',
                        \Graphpinator\Container\Container::Boolean(),
                        static function (\stdClass $parent, $bool) {
                            return $parent->bool
                                ?? $bool;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'bool',
                            \Graphpinator\Container\Container::Boolean(),
                        )->setDefaultValue(false),
                    ])),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getUnionInvalidResolvedType() : \Graphpinator\Type\UnionType
    {
        return new class extends \Graphpinator\Type\UnionType
        {
            protected const NAME = 'TestUnionInvalidResolvedType';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Utils\ConcreteSet([
                    TestSchema::getTypeAbc(),
                ]));
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(TestSchema::getTypeZzz(), $rawValue);
            }
        };
    }

    public static function getUnion() : \Graphpinator\Type\UnionType
    {
        return new class extends \Graphpinator\Type\UnionType
        {
            protected const NAME = 'TestUnion';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Utils\ConcreteSet([
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

    public static function getSimpleEnum() : \Graphpinator\Type\EnumType
    {
        return new class extends \Graphpinator\Type\EnumType
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

    public static function getArrayEnum() : \Graphpinator\Type\EnumType
    {
        return new class extends \Graphpinator\Type\EnumType
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

    public static function getDescriptionEnum() : \Graphpinator\Type\EnumType
    {
        return new class extends \Graphpinator\Type\EnumType
        {
            protected const NAME = 'DescriptionEnum';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Type\Enum\EnumItemSet([
                    new \Graphpinator\Type\Enum\EnumItem('A', 'single line description'),
                    (new \Graphpinator\Type\Enum\EnumItem('B'))
                        ->setDeprecated(),
                    new \Graphpinator\Type\Enum\EnumItem('C', 'multi line' . \PHP_EOL . 'description'),
                    (new \Graphpinator\Type\Enum\EnumItem('D', 'single line description'))
                        ->setDeprecated('reason'),
                ]));
            }
        };
    }

    public static function getTestScalar() : \Graphpinator\Type\Scalar\ScalarType
    {
        return new class extends \Graphpinator\Type\Scalar\ScalarType
        {
            protected const NAME = 'TestScalar';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTestDirective() : \Graphpinator\Directive\Directive
    {
        return new class extends \Graphpinator\Directive\Directive implements \Graphpinator\Directive\Contract\ExecutableDefinition
        {
            use \Graphpinator\Directive\Contract\TExecutableDefinition;

            protected const NAME = 'testDirective';
            public static $count = 0;

            public function __construct()
            {
                parent::__construct(
                    [\Graphpinator\Directive\ExecutableDirectiveLocation::FIELD],
                    true,
                );

                $this->fieldBeforeFn = static function() {
                    ++self::$count;

                    return \Graphpinator\Directive\FieldDirectiveResult::NONE;
                };
            }

            public function validateType(
                ?\Graphpinator\Type\Contract\Definition $definition,
                \Graphpinator\Value\ArgumentValueSet $arguments,
            ) : bool
            {
                return true;
            }

            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet();
            }
        };
    }

    public static function getInvalidDirectiveResult() : \Graphpinator\Directive\Directive
    {
        return new class extends \Graphpinator\Directive\Directive implements \Graphpinator\Directive\Contract\ExecutableDefinition
        {
            use \Graphpinator\Directive\Contract\TExecutableDefinition;

            protected const NAME = 'invalidDirectiveResult';

            public function __construct()
            {
                parent::__construct(
                    [\Graphpinator\Directive\ExecutableDirectiveLocation::FIELD],
                    true,
                );

                $this->fieldBeforeFn = static function() : string {
                    return 'random';
                };
            }

            public function validateType(
                ?\Graphpinator\Type\Contract\Definition $definition,
                \Graphpinator\Value\ArgumentValueSet $arguments,
            ) : bool
            {
                return true;
            }

            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet();
            }
        };
    }

    public static function getInvalidDirectiveType() : \Graphpinator\Directive\Directive
    {
        return new class extends \Graphpinator\Directive\Directive implements \Graphpinator\Directive\Contract\ExecutableDefinition
        {
            use \Graphpinator\Directive\Contract\TExecutableDefinition;

            protected const NAME = 'invalidDirectiveType';

            public function __construct()
            {
                parent::__construct(
                    [\Graphpinator\Directive\ExecutableDirectiveLocation::FIELD],
                    false,
                );
            }

            public function validateType(
                ?\Graphpinator\Type\Contract\Definition $definition,
                \Graphpinator\Value\ArgumentValueSet $arguments,
            ) : bool
            {
                return false;
            }

            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet();
            }
        };
    }

    public static function getComplexDefaultsInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'ComplexDefaultsInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
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
                    \Graphpinator\Argument\Argument::create(
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

    public static function getUploadType() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'UploadType';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'fileName',
                        \Graphpinator\Container\Container::String(),
                        static function (\Psr\Http\Message\UploadedFileInterface $file) : string {
                            return $file->getClientFilename();
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fileContent',
                        \Graphpinator\Container\Container::String(),
                        static function (\Psr\Http\Message\UploadedFileInterface $file) : string {
                            return $file->getStream()->getContents();
                        },
                    ),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getUploadInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'UploadInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'file',
                        new \Graphpinator\Module\Upload\UploadType(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'files',
                        (new \Graphpinator\Module\Upload\UploadType())->list(),
                    ),
                ]);
            }
        };
    }

    public static function getSimpleType() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'SimpleType';
            protected const DESCRIPTION = 'Simple desc';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldName',
                        \Graphpinator\Container\Container::String()->notNull(),
                        static function ($parent, $name) {
                            return $parent->name
                                ?? $name;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'name',
                            \Graphpinator\Container\Container::String()->notNull(),
                        )->setDefaultValue('testValue'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldNumber',
                        \Graphpinator\Container\Container::Int()->notNullList(),
                        static function ($parent, $number) {
                            return $parent->number
                                ?? $number;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'number',
                            \Graphpinator\Container\Container::Int()->notNullList(),
                        )->setDefaultValue([1, 2]),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldBool',
                        \Graphpinator\Container\Container::Boolean(),
                        static function ($parent, $bool) {
                            return $parent->bool
                                ?? $bool;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'bool',
                            \Graphpinator\Container\Container::Boolean(),
                        )->setDefaultValue(true),
                    ])),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getSimpleEmptyTestInput() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'SimpleEmptyTestInput';
            protected const DESCRIPTION = null;

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNumber',
                        \Graphpinator\Container\Container::Int(),
                        static function (\stdClass $parent) {
                            return $parent->number
                                ?? null;
                        },
                    ),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getNullFieldResolution() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'NullFieldResolution';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    \Graphpinator\Field\ResolvableField::create(
                        'stringType',
                        \Graphpinator\Container\Container::String()->notNull(),
                        static function ($parent, $string) {
                            return $string;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'nullString',
                            \Graphpinator\Container\Container::String(),
                        )->setDefaultValue(null),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'interfaceType',
                        TestSchema::getInterface()->notNull(),
                        static function ($parent, $interface) {
                            return $interface;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'nullInterface',
                            \Graphpinator\Container\Container::String(),
                        )->setDefaultValue(null),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'unionType',
                        TestSchema::getUnion()->notNull(),
                        static function ($parent, $union) {
                            return $union;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'nullUnion',
                            \Graphpinator\Container\Container::String(),
                        )->setDefaultValue(null),
                    ])),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getNullListResolution() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'NullListResolution';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    \Graphpinator\Field\ResolvableField::create(
                        'stringListType',
                        \Graphpinator\Container\Container::String()->notNullList(),
                        static function ($parent, $string) {
                            return $string;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'nullString',
                            \Graphpinator\Container\Container::String(),
                        )->setDefaultValue(null),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'interfaceListType',
                        TestSchema::getInterface()->notNullList(),
                        static function ($parent, $interface) {
                            return $interface;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'nullInterface',
                            TestSchema::getInterface()->list(),
                        )->setDefaultValue(null),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'unionListType',
                        TestSchema::getUnion()->notNullList(),
                        static function ($parent, $union) {
                            return $union;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'nullUnion',
                            TestSchema::getUnion()->list(),
                        )->setDefaultValue(null),
                    ])),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }
}
