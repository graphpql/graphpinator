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
            'NullFieldResolution' => self::getNullFieldResolution(),
            'NullListResolution' => self::getNullListResolution(),
            'SimpleType' => self::getSimpleType(),
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
                    new \Graphpinator\Field\ResolvableField(
                        'fieldConstraint',
                        \Graphpinator\Container\Container::Int(),
                        static function ($parent, \stdClass $arg) : int {
                            return 1;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'arg',
                                TestSchema::getConstraintInput(),
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldExactlyOne',
                        \Graphpinator\Container\Container::Int(),
                        static function ($parent, \stdClass $arg) : int {
                            return 1;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'arg',
                                TestSchema::getExactlyOneInput(),
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldThrow',
                        TestSchema::getUnionInvalidResolvedType(),
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
                    new \Graphpinator\Field\ResolvableField(
                        'fieldUpload',
                        TestSchema::getUploadType()->notNull(),
                        static function ($parent, ?\Psr\Http\Message\UploadedFileInterface $file) : \Psr\Http\Message\UploadedFileInterface {
                            return $file;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'file',
                                new \Graphpinator\Module\Upload\UploadType(),
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldMultiUpload',
                        TestSchema::getUploadType()->notNullList(),
                        static function ($parent, array $files) : array {
                            return $files;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'files',
                                (new \Graphpinator\Module\Upload\UploadType())->list(),
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldInputUpload',
                        TestSchema::getUploadType()->notNull(),
                        static function ($parent, \stdClass $fileInput) : \Psr\Http\Message\UploadedFileInterface {
                            return $fileInput->file;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'fileInput',
                                TestSchema::getUploadInput()->notNull(),
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldInputMultiUpload',
                        TestSchema::getUploadType()->notNullList(),
                        static function ($parent, \stdClass $fileInput) : array {
                            return $fileInput->files;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'fileInput',
                                TestSchema::getUploadInput()->notNull(),
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldMultiInputUpload',
                        TestSchema::getUploadType()->notNullList(),
                        static function ($parent, array $fileInputs) {
                            $return = [];

                            foreach ($fileInputs as $fileInput) {
                                $return[] = $fileInput->file;
                            }

                            return $return;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'fileInputs',
                                TestSchema::getUploadInput()->notNullList(),
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldMultiInputMultiUpload',
                        TestSchema::getUploadType()->notNullList(),
                        static function ($parent, array $fileInputs) {
                            $return = [];

                            foreach ($fileInputs as $fileInput) {
                                $return += $fileInput->files;
                            }

                            return $return;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'fileInputs',
                                TestSchema::getUploadInput()->notNullList(),
                            ),
                        ]),
                    ),
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
                    new \Graphpinator\Field\ResolvableField(
                        'fieldArgumentDefaults',
                        TestSchema::getSimpleType()->notNull(),
                        static function () {
                            return [];
                        },
                    ),
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
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
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

            protected function validateNonNullValue($rawValue) : bool
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
                                        }

                                        $str .= $key . ': ' . $print . '; ';
                                    }

                                    return $str;
                                };

                                $object->name = $concat($arg2);
                            }

                            return $object;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument('arg1', \Graphpinator\Container\Container::Int(), 123),
                            new \Graphpinator\Argument\Argument('arg2', TestSchema::getCompositeInput()),
                        ]),
                    ))->setDeprecated(true),
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

            protected function validateNonNullValue($rawValue) : bool
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

            protected function validateNonNullValue($rawValue) : bool
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
                    new \Graphpinator\Argument\Argument(
                        'scalar',
                        \Graphpinator\Container\Container::String()->notNull(),
                        'defaultString',
                    ),
                    new \Graphpinator\Argument\Argument(
                        'enum',
                        TestSchema::getSimpleEnum()->notNull(),
                        'A',
                    ),
                    new \Graphpinator\Argument\Argument(
                        'list',
                        \Graphpinator\Container\Container::String()->notNullList(),
                        ['string1', 'string2'],
                    ),
                    new \Graphpinator\Argument\Argument(
                        'object',
                        TestSchema::getSimpleInput()->notNull(),
                        (object) ['name' => 'string', 'number' => [1, 2]],
                    ),
                    new \Graphpinator\Argument\Argument(
                        'listObjects',
                        TestSchema::getSimpleInput()->notNullList(),
                        [(object) ['name' => 'string', 'number' => [1]], (object) ['name' => 'string', 'number' => []]],
                    ),
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
                    new \Graphpinator\Utils\InterfaceSet([]),
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

            protected function validateNonNullValue($rawValue) : bool
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
                if (\is_array($rawValue)) {
                    foreach ($rawValue as $value) {
                        return $value === 1
                            ? new \Graphpinator\Value\TypeIntermediateValue(TestSchema::getTypeAbc(), $value)
                            : new \Graphpinator\Value\TypeIntermediateValue(TestSchema::getTypeXyz(), $value);
                    }
                }

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

            protected function validateNonNullValue($rawValue) : bool
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
                    new \Graphpinator\Argument\ArgumentSet([]),
                    static function() {
                        ++self::$count;

                        return \Graphpinator\Directive\DirectiveResult::NONE;
                    },
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
                    new \Graphpinator\Argument\ArgumentSet([]),
                    static function() {
                        return 'blahblah';
                    },
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
                    new \Graphpinator\Argument\Argument(
                        'innerObject',
                        TestSchema::getCompositeInput(),
                        (object) [
                            'name' => 'testName',
                            'inner' => (object) ['name' => 'string', 'number' => [1, 2, 3]],
                            'innerList' => [
                                (object) ['name' => 'string', 'number' => [1]],
                                (object) ['name' => 'string', 'number' => [1, 2, 3, 4]],
                            ],
                            'innerNotNull' => (object) ['name' => 'string', 'number' => [1, 2]],
                        ],
                    ),
                    new \Graphpinator\Argument\Argument(
                        'innerListObjects',
                        TestSchema::getCompositeInput()->list(),
                        [
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
                        ],
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
                    new \Graphpinator\Field\ResolvableField(
                        'dateTime',
                        new \Graphpinator\Type\Addon\DateTimeType(),
                        static function ($parent, string $dateTime) : string {
                            return $dateTime;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'dateTime',
                                new \Graphpinator\Type\Addon\DateTimeType(),
                                '2010-01-01 12:12:50',
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'date',
                        new \Graphpinator\Type\Addon\DateType(),
                        static function ($parent, string $date) : string {
                            return $date;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'date',
                                new \Graphpinator\Type\Addon\DateType(),
                                '2010-01-01',
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'emailAddress',
                        new \Graphpinator\Type\Addon\EmailAddressType(),
                        static function ($parent, string $emailAddress) : string {
                            return $emailAddress;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'emailAddress',
                                new \Graphpinator\Type\Addon\EmailAddressType(),
                                'test@test.com',
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'hsla',
                        new \Graphpinator\Type\Addon\HslaType(),
                        static function ($parent, \stdClass $hsla) : \stdClass {
                            return $hsla;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'hsla',
                                new \Graphpinator\Type\Addon\HslaInput(),
                                (object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5],
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'hsl',
                        new \Graphpinator\Type\Addon\HslType(),
                        static function ($parent, \stdClass $hsl) : \stdClass {
                            return $hsl;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'hsl',
                                new \Graphpinator\Type\Addon\HslInput(),
                                (object) ['hue' => 180, 'saturation' => 50, 'lightness' => 50],
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'ipv4',
                        new \Graphpinator\Type\Addon\IPv4Type(),
                        static function ($parent, string $ipv4) : string {
                            return $ipv4;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'ipv4',
                                new \Graphpinator\Type\Addon\IPv4Type(),
                                '128.0.1.1',
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'ipv6',
                        new \Graphpinator\Type\Addon\IPv6Type(),
                        static function ($parent, string $ipv6) : string {
                            return $ipv6;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'ipv6',
                                new \Graphpinator\Type\Addon\IPv6Type(),
                                'AAAA:1111:FFFF:9999:1111:AAAA:9999:FFFF',
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'json',
                        new \Graphpinator\Type\Addon\JsonType(),
                        static function ($parent, string $json) : string {
                            return $json;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'json',
                                new \Graphpinator\Type\Addon\JsonType(),
                                '{"testName":"testValue"}',
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'mac',
                        new \Graphpinator\Type\Addon\MacType(),
                        static function ($parent, string $mac) : string {
                            return $mac;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'mac',
                                new \Graphpinator\Type\Addon\MacType(),
                                'AA:11:FF:99:11:AA',
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'phoneNumber',
                        new \Graphpinator\Type\Addon\PhoneNumberType(),
                        static function ($parent, string $phoneNumber) : string {
                            return $phoneNumber;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'phoneNumber',
                                new \Graphpinator\Type\Addon\PhoneNumberType(),
                                '+999123456789',
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'postalCode',
                        new \Graphpinator\Type\Addon\PostalCodeType(),
                        static function ($parent, string $postalCode) : string {
                            return $postalCode;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'postalCode',
                                new \Graphpinator\Type\Addon\PostalCodeType(),
                                '111 22',
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'rgba',
                        new \Graphpinator\Type\Addon\RgbaType(),
                        static function ($parent, \stdClass $rgba) : \stdClass {
                            return $rgba;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'rgba',
                                new \Graphpinator\Type\Addon\RgbaInput(),
                                (object) ['red' => 150, 'green' => 150, 'blue' => 150, 'alpha' => 0.5],
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'rgb',
                        new \Graphpinator\Type\Addon\RgbType(),
                        static function ($parent, \stdClass $rgb) : \stdClass {
                            return $rgb;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'rgb',
                                new \Graphpinator\Type\Addon\RgbInput(),
                                (object) ['red' => 150, 'green' => 150, 'blue' => 150],
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'time',
                        new \Graphpinator\Type\Addon\TimeType(),
                        static function ($parent, string $time) : string {
                            return $time;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'time',
                                new \Graphpinator\Type\Addon\TimeType(),
                                '12:12:50',
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'url',
                        new \Graphpinator\Type\Addon\UrlType(),
                        static function ($parent, string $url) : string {
                            return $url;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'url',
                                new \Graphpinator\Type\Addon\UrlType(),
                                'https://test.com/boo/blah.php?testValue=test&testName=name',
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'void',
                        new \Graphpinator\Type\Addon\VoidType(),
                        static function ($parent, $void) {
                            return $void;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'void',
                                new \Graphpinator\Type\Addon\VoidType(),
                                null,
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'gps',
                        new \Graphpinator\Type\Addon\GpsType(),
                        static function ($parent, \stdClass $gps) : \stdClass {
                            return $gps;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'gps',
                                new \Graphpinator\Type\Addon\GpsInput(),
                                (object) ['lat' => 45.0, 'lng' => 90.0],
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'point',
                        new \Graphpinator\Type\Addon\PointType(),
                        static function ($parent, \stdClass $point) : \stdClass {
                            return $point;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'point',
                                new \Graphpinator\Type\Addon\PointInput(),
                                (object) ['x' => 420.42, 'y' => 420.42],
                            ),
                        ]),
                    ),
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
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

            protected function validateNonNullValue($rawValue) : bool
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
                    new \Graphpinator\Field\ResolvableField(
                        'fieldName',
                        \Graphpinator\Container\Container::String()->notNull(),
                        static function ($parent, $name) {
                            return $name;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'name',
                                \Graphpinator\Container\Container::String()->notNull(),
                                'testValue',
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNumber',
                        \Graphpinator\Container\Container::Int()->notNullList(),
                        static function ($parent, $number) {
                            return $number;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'number',
                                \Graphpinator\Container\Container::Int()->notNullList(),
                                [1, 2],
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldBool',
                        \Graphpinator\Container\Container::Boolean(),
                        static function ($parent, $bool) {
                            return $bool;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'bool',
                                \Graphpinator\Container\Container::Boolean(),
                                true,
                            ),
                        ]),
                    ),
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
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
                    new \Graphpinator\Field\ResolvableField(
                        'stringType',
                        \Graphpinator\Container\Container::String()->notNull(),
                        static function ($parent, $string) {
                            return $string;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'nullString',
                                \Graphpinator\Container\Container::String(),
                                null,
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'interfaceType',
                        TestSchema::getInterface()->notNull(),
                        static function ($parent, $interface) {
                            return $interface;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'nullInterface',
                                \Graphpinator\Container\Container::String(),
                                null,
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'unionType',
                        TestSchema::getUnion()->notNull(),
                        static function ($parent, $union) {
                            return $union;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'nullUnion',
                                \Graphpinator\Container\Container::String(),
                                null,
                            ),
                        ]),
                    ),
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
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
                    new \Graphpinator\Field\ResolvableField(
                        'stringListType',
                        \Graphpinator\Container\Container::String()->notNullList(),
                        static function ($parent, $string) {
                            return $string;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'nullString',
                                \Graphpinator\Container\Container::String(),
                                null,
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'interfaceListType',
                        TestSchema::getInterface()->notNullList(),
                        static function ($parent, $interface) {
                            return $interface;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'nullInterface',
                                TestSchema::getInterface()->list(),
                                null,
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'unionListType',
                        TestSchema::getUnion()->notNullList(),
                        static function ($parent, $union) {
                            return $union;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'nullUnion',
                                TestSchema::getUnion()->list(),
                                null,
                            ),
                        ]),
                    ),
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }
}
