<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class PrintSchema
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
            'ITestInterface' => self::getInterfaceWithoutDescription(),
            'TestInterface' => self::getInterface(),
            'ITestInterface2' => self::getInterfaceWithDescription(),
            'UTestUnion' => self::getUnionWithDescription(),
            'TestUnion' => self::getUnion(),
            'TestInput' => self::getInput(),
            'TestInnerInput' => self::getInnerInput(),
            'TestEnum' => self::getEnum(),
            'TestExplicitEnum' => self::getExplicitEnum(),
            'ETestEnum' => self::getEnumWithDescription(),
            'TestSecondScalar' => self::getTestSecondScalar(),
            'TestScalar' => self::getTestScalar(),
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
            protected const DESCRIPTION = 'Test Abc description';

            protected function validateNonNullValue($rawValue) : bool
            {
                return $rawValue === 1;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    (new \Graphpinator\Field\ResolvableField(
                        'field1',
                        TestSchema::getInterface(),
                        static function (int $parent, \Graphpinator\Resolver\ArgumentValueSet $args) {
                            return 1;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument('arg1', \Graphpinator\Type\Container\Container::Int(), 123),
                            new \Graphpinator\Argument\Argument('arg2', TestSchema::getInput()),
                        ]),
                    ))->setDeprecated(true)->setDeprecationReason('Test deprecation reason'),
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
                    new \Graphpinator\Argument\Argument('name', \Graphpinator\Type\Container\Container::String()->notNull()),
                    (new \Graphpinator\Argument\Argument(
                        'inner',
                        TestSchema::getInnerInput(),
                    ))->setDescription('multi line' . \PHP_EOL . 'description'),
                    (new \Graphpinator\Argument\Argument(
                        'innerList',
                        TestSchema::getInnerInput()->notNullList(),
                    ))->setDescription('multi line' . \PHP_EOL . 'description'),
                    (new \Graphpinator\Argument\Argument(
                        'innerNotNull',
                        TestSchema::getInnerInput()->notNull(),
                    ))->setDescription('single line description'),
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
                        \Graphpinator\Type\Container\Container::String()->notNull()
                    ),
                    (new \Graphpinator\Argument\Argument(
                        'number',
                        \Graphpinator\Type\Container\Container::Int()->notNullList(),
                    ))->setDescription('single line description'),
                    (new \Graphpinator\Argument\Argument(
                        'bool',
                        \Graphpinator\Type\Container\Container::Boolean(),
                    ))->setDescription('multi line' . \PHP_EOL . 'description'),
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

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field('name', \Graphpinator\Type\Container\Container::String()->notNull()),
                ]);
            }
        };
    }

    public static function getInterfaceWithoutDescription() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType
        {
            protected const NAME = 'ITestInterface';

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field('name', \Graphpinator\Type\Container\Container::String()->notNull()),
                ]);
            }
        };
    }

    public static function getInterfaceWithDescription() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType
        {
            protected const NAME = 'ITestInterface2';
            protected const DESCRIPTION = 'ITestInterface2 Description';

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    (new \Graphpinator\Field\Field('name', \Graphpinator\Type\Container\Container::String()->notNull()))
                        ->setDescription('single line description'),
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
                    PrintSchema::getTypeAbc(),
                    PrintSchema::getTypeXyz(),
                ]));
            }
        };
    }

    public static function getUnionWithDescription() : \Graphpinator\Type\UnionType
    {
        return new class extends \Graphpinator\Type\UnionType
        {
            protected const NAME = 'UTestUnion';
            protected const DESCRIPTION = 'UTestUnion description';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Utils\ConcreteSet([
                    PrintSchema::getTypeAbc(),
                    PrintSchema::getTypeXyz(),
                ]));
            }
        };
    }

    public static function getEnum() : \Graphpinator\Type\EnumType
    {
        return new class extends \Graphpinator\Type\EnumType
        {
            public const A = 'a';
            public const B = ['b', 'single line description'];
            public const C = 'c';
            public const D = ['d', 'multi line' . \PHP_EOL . 'description'];

            protected const NAME = 'TestEnum';

            public function __construct()
            {
                parent::__construct(self::fromConstants());
            }
        };
    }

    public static function getEnumWithDescription() : \Graphpinator\Type\EnumType
    {
        return new class extends \Graphpinator\Type\EnumType
        {
            public const A = ['a', 'single line description'];
            public const B = ['b', 'single line description'];
            public const C = ['c', 'single line description'];
            public const D = ['d', 'single line description'];

            protected const NAME = 'ETestEnum';
            protected const DESCRIPTION = 'ETestEnum description';

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
                    (new \Graphpinator\Type\Enum\EnumItem('A'))->setDescription('single line description'),
                    (new \Graphpinator\Type\Enum\EnumItem('B'))->setDeprecated(true),
                    (new \Graphpinator\Type\Enum\EnumItem('C'))->setDescription('multi line' . \PHP_EOL . 'description'),
                    (new \Graphpinator\Type\Enum\EnumItem('D'))->setDescription('single line description 2')
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

    public static function getTestSecondScalar() : \Graphpinator\Type\Scalar\ScalarType
    {
        return new class extends \Graphpinator\Type\Scalar\ScalarType
        {
            protected const NAME = 'TestSecondScalar';

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

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Argument\ArgumentSet([]),
                    static function() {
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
}
