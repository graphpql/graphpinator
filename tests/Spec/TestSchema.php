<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class TestSchema
{
    use \Nette\StaticClass;

    public static function getSchema() : \Graphpinator\Type\Schema
    {
        return new \Graphpinator\Type\Schema(
            self::getTypeResolver(),
            self::getQuery(),
        );
    }

    public static function getFullSchema() : \Graphpinator\Type\Schema
    {
        return new \Graphpinator\Type\Schema(
            self::getTypeResolver(),
            self::getQuery(),
            self::getQuery(),
            self::getQuery(),
        );
    }

    public static function getTypeResolver() : \Graphpinator\Container\Container
    {
        return new \Graphpinator\Container\SimpleContainer([
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
            'Hsla' => new \Graphpinator\Type\Addon\HslaType(),
            'Hsl' => new \Graphpinator\Type\Addon\HslType(),
            'Ipv4' => new \Graphpinator\Type\Addon\IPv4Type(),
            'Ipv6' => new \Graphpinator\Type\Addon\IPv6Type(),
            'Json' => new \Graphpinator\Type\Addon\JsonType(),
            'Mac' => new \Graphpinator\Type\Addon\MacType(),
            'PhoneNumber' => new \Graphpinator\Type\Addon\PhoneNumberType(),
            'PostalCode' => new \Graphpinator\Type\Addon\PostalCodeType(),
            'Rgba' => new \Graphpinator\Type\Addon\RgbaType(),
            'Rgb' => new \Graphpinator\Type\Addon\RgbType(),
            'Time' => new \Graphpinator\Type\Addon\TimeType(),
            'Url' => new \Graphpinator\Type\Addon\UrlType(),
            'Void' => new \Graphpinator\Type\Addon\VoidType(),
            'Upload' => new \Graphpinator\Module\Upload\UploadType(),
            'Gps' => new \Graphpinator\Type\Addon\GpsType(),
            'Point' => new \Graphpinator\Type\Addon\PointType(),
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
        ], [
            'testDirective' => self::getTestDirective(),
            'invalidDirective' => self::getInvalidDirective(),
        ]);
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
                    ))->addConstraint(new \Graphpinator\Constraint\ListConstraint(3, 5))
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
                    ))->setDeprecated(true)->setArguments(new \Graphpinator\Argument\ArgumentSet([
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
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet(),
                );

                $this->addConstraint(new \Graphpinator\Constraint\ObjectConstraint([
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
                ]));
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
                        static function () {
                            return 1;
                        },
                    ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(-20)),
                    (new \Graphpinator\Field\ResolvableField(
                        'intMaxField',
                        \Graphpinator\Container\Container::Int(),
                        static function () {
                            return 1;
                        },
                    ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(null, 20)),
                    (new \Graphpinator\Field\ResolvableField(
                        'intOneOfField',
                        \Graphpinator\Container\Container::Int(),
                        static function () {
                            return 1;
                        },
                    ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(null, null, [1, 2, 3])),
                    (new \Graphpinator\Field\ResolvableField(
                        'floatMinField',
                        \Graphpinator\Container\Container::Float(),
                        static function () {
                            return 4.02;
                        },
                    ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(4.01)),
                    (new \Graphpinator\Field\ResolvableField(
                        'floatMaxField',
                        \Graphpinator\Container\Container::Float(),
                        static function () {
                            return 1.1;
                        },
                    ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(null, 20.101)),
                    (new \Graphpinator\Field\ResolvableField(
                        'floatOneOfField',
                        \Graphpinator\Container\Container::Float(),
                        static function () {
                            return 1.01;
                        },
                    ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(null, null, [1.01, 2.02, 3.0])),
                    (new \Graphpinator\Field\ResolvableField(
                        'stringMinField',
                        \Graphpinator\Container\Container::String(),
                        static function () {
                            return 1;
                        },
                    ))->addConstraint(new \Graphpinator\Constraint\StringConstraint(4)),
                    (new \Graphpinator\Field\ResolvableField(
                        'stringMaxField',
                        \Graphpinator\Container\Container::String(),
                        static function () {
                            return 1;
                        },
                    ))->addConstraint(new \Graphpinator\Constraint\StringConstraint(null, 10)),
                    (new \Graphpinator\Field\ResolvableField(
                        'listMinField',
                        \Graphpinator\Container\Container::Int()->list(),
                        static function () {
                            return [1];
                        },
                    ))->addConstraint(new \Graphpinator\Constraint\ListConstraint(1)),
                    (new \Graphpinator\Field\ResolvableField(
                        'listMaxField',
                        \Graphpinator\Container\Container::Int()->list(),
                        static function () {
                            return [1, 2];
                        },
                    ))->addConstraint(new \Graphpinator\Constraint\ListConstraint(null, 3)),
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
                $this->addConstraint(new \Graphpinator\Constraint\ObjectConstraint([
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
                    'stringOneOfEmptyArg',
                    'listMinArg',
                    'listMaxArg',
                    'listUniqueArg',
                    'listInnerListArg',
                    'listMinIntMinArg',
                ]));
            }

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'intMinArg',
                        \Graphpinator\Container\Container::Int(),
                    ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(-20)),
                    (new \Graphpinator\Argument\Argument(
                        'intMaxArg',
                        \Graphpinator\Container\Container::Int(),
                    ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(null, 20)),
                    (new \Graphpinator\Argument\Argument(
                        'intOneOfArg',
                        \Graphpinator\Container\Container::Int(),
                    ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(null, null, [1, 2, 3])),
                    (new \Graphpinator\Argument\Argument(
                        'floatMinArg',
                        \Graphpinator\Container\Container::Float(),
                    ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(4.01)),
                    (new \Graphpinator\Argument\Argument(
                        'floatMaxArg',
                        \Graphpinator\Container\Container::Float(),
                    ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(null, 20.101)),
                    (new \Graphpinator\Argument\Argument(
                        'floatOneOfArg',
                        \Graphpinator\Container\Container::Float(),
                    ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(null, null, [1.01, 2.02, 3.0])),
                    (new \Graphpinator\Argument\Argument(
                        'stringMinArg',
                        \Graphpinator\Container\Container::String(),
                    ))->addConstraint(new \Graphpinator\Constraint\StringConstraint(4)),
                    (new \Graphpinator\Argument\Argument(
                        'stringMaxArg',
                        \Graphpinator\Container\Container::String(),
                    ))->addConstraint(new \Graphpinator\Constraint\StringConstraint(null, 10)),
                    (new \Graphpinator\Argument\Argument(
                        'stringRegexArg',
                        \Graphpinator\Container\Container::String(),
                    ))->addConstraint(new \Graphpinator\Constraint\StringConstraint(null, null, '/^(abc)|(foo)$/')),
                    (new \Graphpinator\Argument\Argument(
                        'stringOneOfArg',
                        \Graphpinator\Container\Container::String(),
                    ))->addConstraint(new \Graphpinator\Constraint\StringConstraint(null, null, null, ['abc', 'foo'])),
                    (new \Graphpinator\Argument\Argument(
                        'stringOneOfEmptyArg',
                        \Graphpinator\Container\Container::String(),
                    ))->addConstraint(new \Graphpinator\Constraint\StringConstraint(null, null, null, [])),
                    (new \Graphpinator\Argument\Argument(
                        'listMinArg',
                        \Graphpinator\Container\Container::Int()->list(),
                    ))->addConstraint(new \Graphpinator\Constraint\ListConstraint(1)),
                    (new \Graphpinator\Argument\Argument(
                        'listMaxArg',
                        \Graphpinator\Container\Container::Int()->list(),
                    ))->addConstraint(new \Graphpinator\Constraint\ListConstraint(null, 3)),
                    (new \Graphpinator\Argument\Argument(
                        'listUniqueArg',
                        \Graphpinator\Container\Container::Int()->list(),
                    ))->addConstraint(new \Graphpinator\Constraint\ListConstraint(null, null, true)),
                    (new \Graphpinator\Argument\Argument(
                        'listInnerListArg',
                        \Graphpinator\Container\Container::Int()->list()->list(),
                    ))->addConstraint(new \Graphpinator\Constraint\ListConstraint(null, null, false, (object) [
                        'minItems' => 1,
                        'maxItems' => 3,
                    ])),
                    (new \Graphpinator\Argument\Argument(
                        'listMinIntMinArg',
                        \Graphpinator\Container\Container::Int()->list(),
                    ))->addConstraint(new \Graphpinator\Constraint\ListConstraint(3))
                    ->addConstraint(new \Graphpinator\Constraint\IntConstraint(3)),
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
                $this->addConstraint(new \Graphpinator\Constraint\ObjectConstraint(null, [
                    'int1',
                    'int2',
                ]));
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
                        (new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Container\Container::String(),
                        ))->setDefaultValue('testValue'),
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
            public const A = ['A', 'First description'];
            public const B = ['B', 'Second description'];
            public const C = ['C', 'Third description'];

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
                        ->setDeprecated(true),
                    new \Graphpinator\Type\Enum\EnumItem('C', 'multi line' . \PHP_EOL . 'description'),
                    (new \Graphpinator\Type\Enum\EnumItem('D', 'single line description'))
                        ->setDeprecated(true)
                        ->setDeprecationReason('reason'),
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
        return new class extends \Graphpinator\Directive\ExecutableDirective
        {
            protected const NAME = 'testDirective';
            public static $count = 0;

            public function __construct()
            {
                parent::__construct(
                    [\Graphpinator\Directive\ExecutableDirectiveLocation::FIELD],
                    true,
                    new \Graphpinator\Argument\ArgumentSet(),
                    static function() {
                        ++self::$count;

                        return \Graphpinator\Directive\DirectiveResult::NONE;
                    },
                    null,
                );
            }
        };
    }

    public static function getInvalidDirective() : \Graphpinator\Directive\Directive
    {
        return new class extends \Graphpinator\Directive\ExecutableDirective
        {
            protected const NAME = 'invalidDirective';

            public function __construct()
            {
                parent::__construct(
                    [\Graphpinator\Directive\ExecutableDirectiveLocation::FIELD],
                    true,
                    new \Graphpinator\Argument\ArgumentSet(),
                    static function() {
                        return 'blahblah';
                    },
                    null,
                );
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

                $this->addConstraint(new \Graphpinator\Constraint\ObjectConstraint(null, ['fieldA', 'fieldB']));
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
                        new \Graphpinator\Type\Addon\DateTimeType(),
                        static function ($parent, string $dateTime) : string {
                            return $dateTime;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'dateTime',
                            new \Graphpinator\Type\Addon\DateTimeType(),
                        )->setDefaultValue('2010-01-01 12:12:50'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'date',
                        new \Graphpinator\Type\Addon\DateType(),
                        static function ($parent, string $date) : string {
                            return $date;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'date',
                            new \Graphpinator\Type\Addon\DateType(),
                        )->setDefaultValue('2010-01-01'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'emailAddress',
                        new \Graphpinator\Type\Addon\EmailAddressType(),
                        static function ($parent, string $emailAddress) : string {
                            return $emailAddress;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'emailAddress',
                            new \Graphpinator\Type\Addon\EmailAddressType(),
                        )->setDefaultValue('test@test.com'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'hsla',
                        new \Graphpinator\Type\Addon\HslaType(),
                        static function ($parent, \stdClass $hsla) : \stdClass {
                            return $hsla;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'hsla',
                            new \Graphpinator\Type\Addon\HslaInput(),
                        )->setDefaultValue((object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5]),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'hsl',
                        new \Graphpinator\Type\Addon\HslType(),
                        static function ($parent, \stdClass $hsl) : \stdClass {
                            return $hsl;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'hsl',
                            new \Graphpinator\Type\Addon\HslInput(),
                        )->setDefaultValue((object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50]),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'ipv4',
                        new \Graphpinator\Type\Addon\IPv4Type(),
                        static function ($parent, string $ipv4) : string {
                            return $ipv4;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'ipv4',
                            new \Graphpinator\Type\Addon\IPv4Type(),
                        )->setDefaultValue('128.0.1.1'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'ipv6',
                        new \Graphpinator\Type\Addon\IPv6Type(),
                        static function ($parent, string $ipv6) : string {
                            return $ipv6;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'ipv6',
                            new \Graphpinator\Type\Addon\IPv6Type(),
                        )->setDefaultValue('AAAA:1111:FFFF:9999:1111:AAAA:9999:FFFF'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'json',
                        new \Graphpinator\Type\Addon\JsonType(),
                        static function ($parent, string $json) : string {
                            return $json;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'json',
                            new \Graphpinator\Type\Addon\JsonType(),
                        )->setDefaultValue('{"testName":"testValue"}'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'mac',
                        new \Graphpinator\Type\Addon\MacType(),
                        static function ($parent, string $mac) : string {
                            return $mac;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'mac',
                            new \Graphpinator\Type\Addon\MacType(),
                        )->setDefaultValue('AA:11:FF:99:11:AA'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'phoneNumber',
                        new \Graphpinator\Type\Addon\PhoneNumberType(),
                        static function ($parent, string $phoneNumber) : string {
                            return $phoneNumber;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'phoneNumber',
                            new \Graphpinator\Type\Addon\PhoneNumberType(),
                        )->setDefaultValue('+999123456789'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'postalCode',
                        new \Graphpinator\Type\Addon\PostalCodeType(),
                        static function ($parent, string $postalCode) : string {
                            return $postalCode;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'postalCode',
                            new \Graphpinator\Type\Addon\PostalCodeType(),
                        )->setDefaultValue('111 22'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'rgba',
                        new \Graphpinator\Type\Addon\RgbaType(),
                        static function ($parent, \stdClass $rgba) : \stdClass {
                            return $rgba;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'rgba',
                            new \Graphpinator\Type\Addon\RgbaInput(),
                        )->setDefaultValue((object) ['red' => 150, 'green' => 150, 'blue' => 150, 'alpha' => 0.5]),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'rgb',
                        new \Graphpinator\Type\Addon\RgbType(),
                        static function ($parent, \stdClass $rgb) : \stdClass {
                            return $rgb;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'rgb',
                            new \Graphpinator\Type\Addon\RgbInput(),
                        )->setDefaultValue((object) ['red' => 150, 'green' => 150, 'blue' => 150]),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'time',
                        new \Graphpinator\Type\Addon\TimeType(),
                        static function ($parent, string $time) : string {
                            return $time;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'time',
                            new \Graphpinator\Type\Addon\TimeType(),
                        )->setDefaultValue('12:12:50'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'url',
                        new \Graphpinator\Type\Addon\UrlType(),
                        static function ($parent, string $url) : string {
                            return $url;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'url',
                            new \Graphpinator\Type\Addon\UrlType(),
                        )->setDefaultValue('https://test.com/boo/blah.php?testValue=test&testName=name'),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'void',
                        new \Graphpinator\Type\Addon\VoidType(),
                        static function ($parent, $void) {
                            return $void;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'void',
                            new \Graphpinator\Type\Addon\VoidType(),
                        )->setDefaultValue(null),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'gps',
                        new \Graphpinator\Type\Addon\GpsType(),
                        static function ($parent, \stdClass $gps) : \stdClass {
                            return $gps;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'gps',
                            new \Graphpinator\Type\Addon\GpsInput(),
                        )->setDefaultValue((object) ['lat' => 45.0, 'lng' => 90.0]),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'point',
                        new \Graphpinator\Type\Addon\PointType(),
                        static function ($parent, \stdClass $point) : \stdClass {
                            return $point;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'point',
                            new \Graphpinator\Type\Addon\PointInput(),
                        )->setDefaultValue((object) ['x' => 420.42, 'y' => 420.42]),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'bigInt',
                        new \Graphpinator\Type\Addon\BigIntType(),
                        static function ($parent, int $bigInt) : int {
                            return $bigInt;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'bigInt',
                            new \Graphpinator\Type\Addon\BigIntType(),
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
