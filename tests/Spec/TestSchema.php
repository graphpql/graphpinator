<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class TestSchema
{
    use \Nette\StaticClass;

    private static array $types = [];
    private static ?\Graphpinator\Directive\Constraint\ConstraintDirectiveAccessor $accessor = null;
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
            'ConstraintInput' => self::getConstraintInput(),
            'ExactlyOneInput' => self::getExactlyOneInput(),
            'ConstraintType' => self::getConstraintType(),
            'SimpleEnum' => self::getSimpleEnum(),
            'ArrayEnum' => self::getArrayEnum(),
            'DescriptionEnum' => self::getDescriptionEnum(),
            'TestScalar' => self::getTestScalar(),
            'AddonType' => self::getAddonType(),
            'UploadType' => self::getUploadType(),
            'UploadInput' => self::getUploadInput(),
            'ComplexDefaultsInput' => self::getComplexDefaultsInput(),
            'DateTime' => new \Graphpinator\Type\Addon\DateTimeType(),
            'Date' => new \Graphpinator\Type\Addon\DateType(),
            'EmailAddress' => new \Graphpinator\Type\Addon\EmailAddressType(),
            'Hsla' => new \Graphpinator\Type\Addon\HslaType(
                self::getAccessor(),
            ),
            'HslaInput' => new \Graphpinator\Type\Addon\HslaInput(
                self::getAccessor(),
            ),
            'Hsl' => new \Graphpinator\Type\Addon\HslType(
                self::getAccessor(),
            ),
            'HslInput' => new \Graphpinator\Type\Addon\HslInput(
                self::getAccessor(),
            ),
            'Ipv4' => new \Graphpinator\Type\Addon\IPv4Type(),
            'Ipv6' => new \Graphpinator\Type\Addon\IPv6Type(),
            'Json' => new \Graphpinator\Type\Addon\JsonType(),
            'Mac' => new \Graphpinator\Type\Addon\MacType(),
            'PhoneNumber' => new \Graphpinator\Type\Addon\PhoneNumberType(),
            'PostalCode' => new \Graphpinator\Type\Addon\PostalCodeType(),
            'Rgba' => new \Graphpinator\Type\Addon\RgbaType(
                self::getAccessor(),
            ),
            'RgbaInput' => new \Graphpinator\Type\Addon\RgbaInput(
                self::getAccessor(),
            ),
            'Rgb' => new \Graphpinator\Type\Addon\RgbType(
                self::getAccessor(),
            ),
            'RgbInput' => new \Graphpinator\Type\Addon\RgbInput(
                self::getAccessor(),
            ),
            'Time' => new \Graphpinator\Type\Addon\TimeType(),
            'Url' => new \Graphpinator\Type\Addon\UrlType(),
            'Void' => new \Graphpinator\Type\Addon\VoidType(),
            'Upload' => new \Graphpinator\Module\Upload\UploadType(),
            'Gps' => new \Graphpinator\Type\Addon\GpsType(
                self::getAccessor(),
            ),
            'GpsInput' => new \Graphpinator\Type\Addon\GpsInput(
                self::getAccessor(),
            ),
            'Point' => new \Graphpinator\Type\Addon\PointType(),
            'PointInput' => new \Graphpinator\Type\Addon\PointInput(),
            'BigInt' => new \Graphpinator\Type\Addon\BigIntType(),
            'NullFieldResolution' => self::getNullFieldResolution(),
            'NullListResolution' => self::getNullListResolution(),
            'SimpleType' => self::getSimpleType(),
            'InterfaceAbc' => self::getInterfaceAbc(),
            'InterfaceEfg' => self::getInterfaceEfg(),
            'FragmentTypeA' => self::getFragmentTypeA(),
            'FragmentTypeB' => self::getFragmentTypeB(),
            'SimpleEmptyTestInput' => self::getSimpleEmptyTestInput(),
            'InterfaceChildType' => self::getInterfaceChildType(),
            'ListConstraintInput' => new \Graphpinator\Directive\Constraint\ListConstraintInput(
                self::getAccessor(),
            ),
            'testDirective' => self::getTestDirective(),
            'invalidDirectiveResult' => self::getInvalidDirectiveResult(),
            'invalidDirectiveType' => self::getInvalidDirectiveType(),
            'stringFilter' => new \Graphpinator\Directive\Where\StringWhereDirective(),
            'intFilter' => new \Graphpinator\Directive\Where\IntWhereDirective(),
            'floatFilter' => new \Graphpinator\Directive\Where\FloatWhereDirective(),
            'listFilter' => new \Graphpinator\Directive\Where\ListWhereDirective(
                self::getType('intConstraint'),
            ),
            'booleanFilter' => new \Graphpinator\Directive\Where\BooleanWhereDirective(),
            'stringConstraint' => new \Graphpinator\Directive\Constraint\StringConstraintDirective(
                self::getAccessor(),
            ),
            'intConstraint' => new \Graphpinator\Directive\Constraint\IntConstraintDirective(
                self::getAccessor(),
            ),
            'floatConstraint' => new \Graphpinator\Directive\Constraint\FloatConstraintDirective(
                self::getAccessor(),
            ),
            'listConstraint' => new \Graphpinator\Directive\Constraint\ListConstraintDirective(
                self::getAccessor(),
            ),
            'objectConstraint' => new \Graphpinator\Directive\Constraint\ObjectConstraintDirective(
                self::getAccessor(),
            ),
        };

        return self::$types[$name];
    }

    public static function getAccessor() : \Graphpinator\Directive\Constraint\ConstraintDirectiveAccessor
    {
        if (self::$accessor === null) {
            self::$accessor = new class implements \Graphpinator\Directive\Constraint\ConstraintDirectiveAccessor
            {
                public function getString(): \Graphpinator\Directive\Constraint\StringConstraintDirective
                {
                    return TestSchema::getType('stringConstraint');
                }

                public function getInt(): \Graphpinator\Directive\Constraint\IntConstraintDirective
                {
                    return TestSchema::getType('intConstraint');
                }

                public function getFloat(): \Graphpinator\Directive\Constraint\FloatConstraintDirective
                {
                    return TestSchema::getType('floatConstraint');
                }

                public function getList(): \Graphpinator\Directive\Constraint\ListConstraintDirective
                {
                    return TestSchema::getType('listConstraint');
                }

                public function getListInput(): \Graphpinator\Directive\Constraint\ListConstraintInput
                {
                    return TestSchema::getType('ListConstraintInput');
                }

                public function getObject(): \Graphpinator\Directive\Constraint\ObjectConstraintDirective
                {
                    return TestSchema::getType('objectConstraint');
                }
            };
        }

        return self::$accessor;
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
            'ConstraintInput' => self::getType('ConstraintInput'),
            'ExactlyOneInput' => self::getType('ExactlyOneInput'),
            'ConstraintType' => self::getType('ConstraintType'),
            'SimpleEnum' => self::getType('SimpleEnum'),
            'ArrayEnum' => self::getType('ArrayEnum'),
            'DescriptionEnum' => self::getType('DescriptionEnum'),
            'TestScalar' => self::getType('TestScalar'),
            'AddonType' => self::getType('AddonType'),
            'UploadType' => self::getType('UploadType'),
            'UploadInput' => self::getType('UploadInput'),
            'ComplexDefaultsInput' => self::getType('ComplexDefaultsInput'),
            'DateTime' => self::getType('DateTime'),
            'Date' => self::getType('Date'),
            'EmailAddress' => self::getType('EmailAddress'),
            'Hsla' => self::getType('Hsla'),
            'Hsl' => self::getType('Hsl'),
            'Ipv4' => self::getType('Ipv4'),
            'Ipv6' => self::getType('Ipv6'),
            'Json' => self::getType('Json'),
            'Mac' => self::getType('Mac'),
            'PhoneNumber' => self::getType('PhoneNumber'),
            'PostalCode' => self::getType('PostalCode'),
            'Rgba' => self::getType('Rgba'),
            'Rgb' => self::getType('Rgb'),
            'Time' => self::getType('Time'),
            'Url' => self::getType('Url'),
            'Void' => self::getType('Void'),
            'Upload' => self::getType('Upload'),
            'Gps' => self::getType('Gps'),
            'Point' => self::getType('Point'),
            'BigInt' => self::getType('BigInt'),
            'NullFieldResolution' => self::getType('NullFieldResolution'),
            'NullListResolution' => self::getType('NullListResolution'),
            'SimpleType' => self::getType('SimpleType'),
            'InterfaceAbc' => self::getType('InterfaceAbc'),
            'InterfaceEfg' => self::getType('InterfaceEfg'),
            'FragmentTypeA' => self::getType('FragmentTypeA'),
            'FragmentTypeB' => self::getType('FragmentTypeB'),
            'SimpleEmptyTestInput' => self::getType('SimpleEmptyTestInput'),
            'InterfaceChildType' => self::getType('InterfaceChildType'),
            'ListConstraintInput' => self::getType('ListConstraintInput'),
        ], [
            'testDirective' => self::getType('testDirective'),
            'invalidDirectiveResult' => self::getType('invalidDirectiveResult'),
            'invalidDirectiveType' => self::getType('invalidDirectiveType'),
            'stringFilter' => self::getType('stringFilter'),
            'intFilter' => self::getType('intFilter'),
            'floatFilter' => self::getType('floatFilter'),
            'listFilter' => self::getType('listFilter'),
            'booleanFilter' => self::getType('booleanFilter'),
            'stringConstraint' => self::getType('stringConstraint'),
            'intConstraint' => self::getType('intConstraint'),
            'floatConstraint' => self::getType('floatConstraint'),
            'listConstraint' => self::getType('listConstraint'),
            'objectConstraint' => self::getType('objectConstraint'),
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
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldConstraint',
                        \Graphpinator\Container\Container::Int(),
                        static function ($parent, \stdClass $arg) : int {
                            return 1;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'arg',
                            TestSchema::getConstraintInput(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldExactlyOne',
                        \Graphpinator\Container\Container::Int(),
                        static function ($parent, \stdClass $arg) : int {
                            return 1;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'arg',
                            TestSchema::getExactlyOneInput(),
                        ),
                    ])),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldThrow',
                        TestSchema::getTypeAbc(),
                        static function () : void {
                            throw new \Exception('Random exception');
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldAddonType',
                        TestSchema::getAddonType(),
                        static function () {
                            return 1;
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
                    (new \Graphpinator\Field\ResolvableField(
                        'fieldListConstraint',
                        TestSchema::getSimpleType()->list(),
                        static function ($parent, array $arg) : array {
                            return $arg;
                        },
                    ))
                    ->addDirective(
                        TestSchema::getType('listConstraint'),
                        ['minItems' => 3, 'maxItems' => 5],
                    )
                    ->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'arg',
                            TestSchema::getSimpleInput()->list(),
                        ),
                    ]),
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
                    new \Graphpinator\Field\ResolvableField(
                        'fieldAOrB',
                        TestSchema::getAOrBType()->notNull(),
                        static function ($parent) : int {
                            return 0;
                        },
                    ),
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

    public static function getConstraintType() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'ConstraintType';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(
                    TestSchema::getType('objectConstraint'),
                    ['atLeastOne' => [
                        'intMinField',
                        'intMaxField',
                        'intOneOfField',
                        'floatMinField',
                        'floatMaxField',
                        'floatOneOfField',
                        'stringMinField',
                        'stringMaxField',
                        'listMinField',
                        'listMaxField',
                    ]],
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    (new \Graphpinator\Field\ResolvableField(
                        'intMinField',
                        \Graphpinator\Container\Container::Int(),
                        static function () : int {
                            return 1;
                        },
                    ))->addDirective(TestSchema::getType('intConstraint'), ['min' => -20]),
                    (new \Graphpinator\Field\ResolvableField(
                        'intMaxField',
                        \Graphpinator\Container\Container::Int(),
                        static function () : int {
                            return 1;
                        },
                    ))->addDirective(TestSchema::getType('intConstraint'), ['max' => 20]),
                    (new \Graphpinator\Field\ResolvableField(
                        'intOneOfField',
                        \Graphpinator\Container\Container::Int(),
                        static function () : int {
                            return 1;
                        },
                    ))->addDirective(TestSchema::getType('intConstraint'), ['oneOf' => [1, 2 , 3]]),
                    (new \Graphpinator\Field\ResolvableField(
                        'floatMinField',
                        \Graphpinator\Container\Container::Float(),
                        static function () {
                            return 4.02;
                        },
                    ))->addDirective(TestSchema::getType('floatConstraint'), ['min' => 4.01]),
                    (new \Graphpinator\Field\ResolvableField(
                        'floatMaxField',
                        \Graphpinator\Container\Container::Float(),
                        static function () {
                            return 1.1;
                        },
                    ))->addDirective(TestSchema::getType('floatConstraint'), ['max' => 20.101]),
                    (new \Graphpinator\Field\ResolvableField(
                        'floatOneOfField',
                        \Graphpinator\Container\Container::Float(),
                        static function () {
                            return 1.01;
                        },
                    ))->addDirective(TestSchema::getType('floatConstraint'), ['oneOf' => [1.01, 2.02, 3.0]]),
                    (new \Graphpinator\Field\ResolvableField(
                        'stringMinField',
                        \Graphpinator\Container\Container::String(),
                        static function () {
                            return 1;
                        },
                    ))->addDirective(TestSchema::getType('stringConstraint'), ['minLength' => 4]),
                    (new \Graphpinator\Field\ResolvableField(
                        'stringMaxField',
                        \Graphpinator\Container\Container::String(),
                        static function () {
                            return 1;
                        },
                    ))->addDirective(TestSchema::getType('stringConstraint'), ['maxLength' => 10]),
                    (new \Graphpinator\Field\ResolvableField(
                        'listMinField',
                        \Graphpinator\Container\Container::Int()->list(),
                        static function () : array {
                            return [1];
                        },
                    ))->addDirective(TestSchema::getType('listConstraint'), ['minItems' => 1]),
                    (new \Graphpinator\Field\ResolvableField(
                        'listMaxField',
                        \Graphpinator\Container\Container::Int()->list(),
                        static function () : array {
                            return [1, 2];
                        },
                    ))->addDirective(TestSchema::getType('listConstraint'), ['maxItems' => 3]),
                ]);
            }
        };
    }

    public static function getConstraintInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'ConstraintInput';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(
                    TestSchema::getType('objectConstraint'),
                    ['atLeastOne' => [
                        'intMinArg',
                        'intMaxArg',
                        'intOneOfArg',
                        'floatMinArg',
                        'floatMaxArg',
                        'floatOneOfArg',
                        'stringMinArg',
                        'stringMaxArg',
                        'stringRegexArg',
                        'stringOneOfArg',
                        'listMinArg',
                        'listMaxArg',
                        'listUniqueArg',
                        'listInnerListArg',
                        'listMinIntMinArg',
                    ]],
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'intMinArg',
                        \Graphpinator\Container\Container::Int(),
                    ))->addDirective(TestSchema::getType('intConstraint'), ['min' => -20]),
                    (new \Graphpinator\Argument\Argument(
                        'intMaxArg',
                        \Graphpinator\Container\Container::Int(),
                    ))->addDirective(TestSchema::getType('intConstraint'), ['max' => 20]),
                    (new \Graphpinator\Argument\Argument(
                        'intOneOfArg',
                        \Graphpinator\Container\Container::Int(),
                    ))->addDirective(TestSchema::getType('intConstraint'), ['oneOf' => [1, 2, 3]]),
                    (new \Graphpinator\Argument\Argument(
                        'floatMinArg',
                        \Graphpinator\Container\Container::Float(),
                    ))->addDirective(TestSchema::getType('floatConstraint'), ['min' => 4.01]),
                    (new \Graphpinator\Argument\Argument(
                        'floatMaxArg',
                        \Graphpinator\Container\Container::Float(),
                    ))->addDirective(TestSchema::getType('floatConstraint'), ['max' => 20.101]),
                    (new \Graphpinator\Argument\Argument(
                        'floatOneOfArg',
                        \Graphpinator\Container\Container::Float(),
                    ))->addDirective(TestSchema::getType('floatConstraint'), ['oneOf' => [1.01, 2.02, 3.0]]),
                    (new \Graphpinator\Argument\Argument(
                        'stringMinArg',
                        \Graphpinator\Container\Container::String(),
                    ))->addDirective(TestSchema::getType('stringConstraint'), ['minLength' => 4]),
                    (new \Graphpinator\Argument\Argument(
                        'stringMaxArg',
                        \Graphpinator\Container\Container::String(),
                    ))->addDirective(TestSchema::getType('stringConstraint'), ['maxLength' => 10]),
                    (new \Graphpinator\Argument\Argument(
                        'stringRegexArg',
                        \Graphpinator\Container\Container::String(),
                    ))->addDirective(TestSchema::getType('stringConstraint'), ['regex' => '/^(abc)|(foo)$/']),
                    (new \Graphpinator\Argument\Argument(
                        'stringOneOfArg',
                        \Graphpinator\Container\Container::String(),
                    ))->addDirective(TestSchema::getType('stringConstraint'), ['oneOf' => ['abc', 'foo']]),
                    (new \Graphpinator\Argument\Argument(
                        'listMinArg',
                        \Graphpinator\Container\Container::Int()->list(),
                    ))->addDirective(TestSchema::getType('listConstraint'), ['minItems' => 1]),
                    (new \Graphpinator\Argument\Argument(
                        'listMaxArg',
                        \Graphpinator\Container\Container::Int()->list(),
                    ))->addDirective(TestSchema::getType('listConstraint'), ['maxItems' => 3]),
                    (new \Graphpinator\Argument\Argument(
                        'listUniqueArg',
                        \Graphpinator\Container\Container::Int()->list(),
                    ))->addDirective(TestSchema::getType('listConstraint'), ['unique' => true]),
                    (new \Graphpinator\Argument\Argument(
                        'listInnerListArg',
                        \Graphpinator\Container\Container::Int()->list()->list(),
                    ))->addDirective(TestSchema::getType('listConstraint'), ['innerList' => (object) [
                        'minItems' => 1,
                        'maxItems' => 3,
                    ]]),
                    \Graphpinator\Argument\Argument::create('listMinIntMinArg', \Graphpinator\Container\Container::Int()->list())
                        ->addDirective(TestSchema::getType('listConstraint'), ['minItems' => 3])
                        ->addDirective(TestSchema::getType('intConstraint'), ['min' => 3]),
                ]);
            }
        };
    }

    public static function getExactlyOneInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'ExactlyOneInput';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(
                    TestSchema::getType('objectConstraint'),
                    ['exactlyOne' => ['int1', 'int2']],
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'int1',
                        \Graphpinator\Container\Container::Int(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'int2',
                        \Graphpinator\Container\Container::Int(),
                    ),
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

    public static function getAOrBType() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'AOrB';
            protected const DESCRIPTION = 'Graphpinator Constraints: AOrB type';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(
                    TestSchema::getType('objectConstraint'),
                    ['exactlyOne' => ['fieldA', 'fieldB']],
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return \is_int($rawValue) && \in_array($rawValue, [0, 1], true);
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'fieldA',
                        \Graphpinator\Container\Container::Int(),
                        static function (?int $parent) : ?int {
                            return $parent === 1
                                ? 1
                                : null;
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldB',
                        \Graphpinator\Container\Container::Int(),
                        static function (int $parent) : ?int {
                            return $parent === 0
                                ? 1
                                : null;
                        },
                    ),
                ]);
            }
        };
    }

    public static function getAddonType() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'AddonType';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    \Graphpinator\Field\ResolvableField::create(
                        'dateTime',
                        TestSchema::getType('DateTime'),
                        static function ($parent, string $dateTime) : string {
                            return $dateTime;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'dateTime',
                            TestSchema::getType('DateTime'),
                        )->setDefaultValue('2010-01-01 12:12:50'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'date',
                        TestSchema::getType('Date'),
                        static function ($parent, string $date) : string {
                            return $date;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'date',
                            TestSchema::getType('Date'),
                        )->setDefaultValue('2010-01-01'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'emailAddress',
                        TestSchema::getType('EmailAddress'),
                        static function ($parent, string $emailAddress) : string {
                            return $emailAddress;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'emailAddress',
                            TestSchema::getType('EmailAddress'),
                        )->setDefaultValue('test@test.com'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'hsla',
                        TestSchema::getType('Hsla'),
                        static function ($parent, \stdClass $hsla) : \stdClass {
                            return $hsla;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'hsla',
                            TestSchema::getType('HslaInput'),
                        )->setDefaultValue((object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'hsl',
                        TestSchema::getType('Hsl'),
                        static function ($parent, \stdClass $hsl) : \stdClass {
                            return $hsl;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'hsl',
                            TestSchema::getType('HslInput'),
                        )->setDefaultValue((object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50]),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'ipv4',
                        TestSchema::getType('Ipv4'),
                        static function ($parent, string $ipv4) : string {
                            return $ipv4;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'ipv4',
                            TestSchema::getType('Ipv4'),
                        )->setDefaultValue('128.0.1.1'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'ipv6',
                        TestSchema::getType('Ipv6'),
                        static function ($parent, string $ipv6) : string {
                            return $ipv6;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'ipv6',
                            TestSchema::getType('Ipv6'),
                        )->setDefaultValue('AAAA:1111:FFFF:9999:1111:AAAA:9999:FFFF'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'json',
                        TestSchema::getType('Json'),
                        static function ($parent, string $json) : string {
                            return $json;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'json',
                            TestSchema::getType('Json'),
                        )->setDefaultValue('{"testName":"testValue"}'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'mac',
                        TestSchema::getType('Mac'),
                        static function ($parent, string $mac) : string {
                            return $mac;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'mac',
                            TestSchema::getType('Mac'),
                        )->setDefaultValue('AA:11:FF:99:11:AA'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'phoneNumber',
                        TestSchema::getType('PhoneNumber'),
                        static function ($parent, string $phoneNumber) : string {
                            return $phoneNumber;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'phoneNumber',
                            TestSchema::getType('PhoneNumber'),
                        )->setDefaultValue('+999123456789'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'postalCode',
                        TestSchema::getType('PostalCode'),
                        static function ($parent, string $postalCode) : string {
                            return $postalCode;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'postalCode',
                            TestSchema::getType('PostalCode'),
                        )->setDefaultValue('111 22'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'rgba',
                        TestSchema::getType('Rgba'),
                        static function ($parent, \stdClass $rgba) : \stdClass {
                            return $rgba;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'rgba',
                            TestSchema::getType('RgbaInput'),
                        )->setDefaultValue((object) ['red' => 150, 'green' => 150, 'blue' => 150, 'alpha' => 0.5]),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'rgb',
                        TestSchema::getType('Rgb'),
                        static function ($parent, \stdClass $rgb) : \stdClass {
                            return $rgb;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'rgb',
                            TestSchema::getType('RgbInput'),
                        )->setDefaultValue((object) ['red' => 150, 'green' => 150, 'blue' => 150]),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'time',
                        TestSchema::getType('Time'),
                        static function ($parent, string $time) : string {
                            return $time;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'time',
                            TestSchema::getType('Time'),
                        )->setDefaultValue('12:12:50'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'url',
                        TestSchema::getType('Url'),
                        static function ($parent, string $url) : string {
                            return $url;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'url',
                            TestSchema::getType('Url'),
                        )->setDefaultValue('https://test.com/boo/blah.php?testValue=test&testName=name'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'void',
                        TestSchema::getType('Void'),
                        static function ($parent, $void) {
                            return $void;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'void',
                            TestSchema::getType('Void'),
                        )->setDefaultValue(null),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'gps',
                        TestSchema::getType('Gps'),
                        static function ($parent, \stdClass $gps) : \stdClass {
                            return $gps;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'gps',
                            TestSchema::getType('GpsInput'),
                        )->setDefaultValue((object) ['lat' => 45.0, 'lng' => 90.0]),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'point',
                        TestSchema::getType('Point'),
                        static function ($parent, \stdClass $point) : \stdClass {
                            return $point;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'point',
                            TestSchema::getType('PointInput'),
                        )->setDefaultValue((object) ['x' => 420.42, 'y' => 420.42]),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'bigInt',
                        TestSchema::getType('BigInt'),
                        static function ($parent, int $bigInt) : int {
                            return $bigInt;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'bigInt',
                            TestSchema::getType('BigInt'),
                        )->setDefaultValue(\PHP_INT_MAX),
                    ])),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
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
