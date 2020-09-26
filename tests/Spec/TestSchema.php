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

    public static function getTypeResolver() : \Graphpinator\Type\Container\Container
    {
        return new \Graphpinator\Type\Container\SimpleContainer([
            'Query' => self::getQuery(),
            'Abc' => self::getTypeAbc(),
            'Xyz' => self::getTypeXyz(),
            'Zzz' => self::getTypeZzz(),
            'TestInterface' => self::getInterface(),
            'TestUnion' => self::getUnion(),
            'TestInput' => self::getInput(),
            'TestInnerInput' => self::getInnerInput(),
            'TestEnum' => self::getEnum(),
            'TestExplicitEnum' => self::getExplicitEnum(),
            'TestScalar' => self::getTestScalar(),
            'TestAddonType' => self::getAddonType(),
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
                    new \Graphpinator\Field\ResolvableField('field0', TestSchema::getUnion(), static function () {
                        return \Graphpinator\Resolver\FieldResult::fromRaw(TestSchema::getTypeAbc(), 1);
                    }),
                    new \Graphpinator\Field\ResolvableField('fieldInvalidType', TestSchema::getUnion(), static function () {
                        return \Graphpinator\Resolver\FieldResult::fromRaw(\Graphpinator\Type\Container\Container::Int(), 1);
                    }),
                    new \Graphpinator\Field\ResolvableField('fieldAbstract', TestSchema::getUnion(), static function () {
                        return 1;
                    }),
                    new \Graphpinator\Field\ResolvableField('fieldThrow', TestSchema::getUnion(), static function () : void {
                        throw new \Exception('Random exception');
                    }),
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
            protected const DESCRIPTION = null;

            protected function validateNonNullValue($rawValue) : bool
            {
                return $rawValue === 1;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field1',
                        TestSchema::getInterface(),
                        static function (int $parent, \Graphpinator\Resolver\ArgumentValueSet $args) : \Graphpinator\Resolver\FieldResult {
                            $object = new \stdClass();

                            if ($args['arg2']->getRawValue() === null) {
                                $object->name = 'Test ' . $args['arg1']->getRawValue();
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

                                $object->name = $concat($args['arg2']->getRawValue());
                            }

                            return \Graphpinator\Resolver\FieldResult::fromRaw(TestSchema::getTypeXyz(), $object);
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument('arg1', \Graphpinator\Type\Container\Container::Int(), 123),
                        new \Graphpinator\Argument\Argument('arg2', TestSchema::getInput()),
                        ]),
                    ),
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
                        \Graphpinator\Type\Container\Container::String()->notNull(),
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
                    new \Graphpinator\Field\ResolvableField('enumList', TestSchema::getEnum()->list(), static function () {
                        return ['A', 'B'];
                    }),
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'TestInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'name',
                        \Graphpinator\Type\Container\Container::String()->notNull(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'inner',
                        TestSchema::getInnerInput(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'innerList',
                        TestSchema::getInnerInput()->notNullList(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'innerNotNull',
                        TestSchema::getInnerInput()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function getInnerInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'TestInnerInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'name',
                        \Graphpinator\Type\Container\Container::String()->notNull(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'number',
                        \Graphpinator\Type\Container\Container::Int()->notNullList(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'bool',
                        \Graphpinator\Type\Container\Container::Boolean(),
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

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field('name', \Graphpinator\Type\Container\Container::String()->notNull()),
                ]);
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
        };
    }

    public static function getEnum() : \Graphpinator\Type\EnumType
    {
        return new class extends \Graphpinator\Type\EnumType
        {
            public const A = 'a';
            public const B = 'b';
            public const C = 'c';
            public const D = 'd';

            protected const NAME = 'TestEnum';

            public function __construct()
            {
                parent::__construct(self::fromConstants());
            }
        };
    }

    public static function getExplicitEnum() : \Graphpinator\Type\EnumType
    {
        return new class extends \Graphpinator\Type\EnumType
        {
            protected const NAME = 'TestExplicitEnum';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Type\Enum\EnumItemSet([
                    new \Graphpinator\Type\Enum\EnumItem('A'),
                    new \Graphpinator\Type\Enum\EnumItem('B'),
                    new \Graphpinator\Type\Enum\EnumItem('C'),
                    new \Graphpinator\Type\Enum\EnumItem('D'),
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
        return new class extends \Graphpinator\Directive\Directive
        {
            protected const NAME = 'testDirective';
            public static $count = 0;

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Argument\ArgumentSet([]),
                    static function() {
                        ++self::$count;

                        return \Graphpinator\Directive\DirectiveResult::NONE;
                    },
                    [\Graphpinator\Directive\DirectiveLocation::FIELD],
                    true,
                );
            }
        };
    }

    public static function getInvalidDirective() : \Graphpinator\Directive\Directive
    {
        return new class extends \Graphpinator\Directive\Directive
        {
            protected const NAME = 'invalidDirective';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Argument\ArgumentSet([]),
                    static function() {
                        return 'blahblah';
                    },
                    [\Graphpinator\Directive\DirectiveLocation::FIELD],
                    true,
                );
            }
        };
    }

    public static function getAddonType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'TestAddonType';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'DateTimeType',
                        new \Graphpinator\Type\Addon\DateTimeType(),
                        '01-01-2000 04:02:10',
                    ),
                    new \Graphpinator\Argument\Argument(
                        'DateType',
                        new \Graphpinator\Type\Addon\DateType(),
                        '01-01-2000',
                    ),
                    new \Graphpinator\Argument\Argument(
                        'EmailAddressType',
                        new \Graphpinator\Type\Addon\EmailAddressType(),
                        'test@test.com',
                    ),
                    new \Graphpinator\Argument\Argument(
                        'HslaType',
                        new \Graphpinator\Type\Addon\HslaType(),
                        ['hue' => 1, 'saturation' => 2, 'lightness' => 3, 'alpha' => 0.5],
                    ),
                    new \Graphpinator\Argument\Argument(
                        'HslType',
                        new \Graphpinator\Type\Addon\HslType(),
                        ['hue' => 1, 'saturation' => 2, 'lightness' => 3],
                    ),
                    new \Graphpinator\Argument\Argument(
                        'IPv4Type',
                        new \Graphpinator\Type\Addon\IPv4Type(),
                        '128.0.1.0',
                    ),
                    new \Graphpinator\Argument\Argument(
                        'IPv6Type',
                        new \Graphpinator\Type\Addon\IPv6Type(),
                        '2001:0DB8:85A3:0000:0000:8A2E:0370:7334',
                    ),
                    new \Graphpinator\Argument\Argument(
                        'JsonType',
                        new \Graphpinator\Type\Addon\JsonType(),
                        (object) ['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]],
                    ),
                    new \Graphpinator\Argument\Argument(
                        'MacType',
                        new \Graphpinator\Type\Addon\MacType(),
                        '00-D5-61-A2-AB-13',
                    ),
                    new \Graphpinator\Argument\Argument(
                        'PhoneNumberType',
                        new \Graphpinator\Type\Addon\PhoneNumberType(),
                        '+420123456789',
                    ),
                    new \Graphpinator\Argument\Argument(
                        'PostalCodeType',
                        new \Graphpinator\Type\Addon\PostalCodeType(),
                        '111 22',
                    ),
                    new \Graphpinator\Argument\Argument(
                        'RgbaType',
                        new \Graphpinator\Type\Addon\RgbaType(),
                        ['red' => 1, 'green' => 2, 'blue' => 3, 'alpha' => 0.5],
                    ),
                    new \Graphpinator\Argument\Argument(
                        'RgbType',
                        new \Graphpinator\Type\Addon\RgbType(),
                        ['red' => 1, 'green' => 2, 'blue' => 3],
                    ),
                    new \Graphpinator\Argument\Argument(
                        'TimeType',
                        new \Graphpinator\Type\Addon\TimeType(),
                        '04:02:55',
                    ),
                    new \Graphpinator\Argument\Argument(
                        'UrlType',
                        new \Graphpinator\Type\Addon\UrlType(),
                        'www.test.com',
                    ),
                    new \Graphpinator\Argument\Argument(
                        'VoidType',
                        new \Graphpinator\Type\Addon\VoidType(),
                    ),
                ]);
            }
        };
    }
}
