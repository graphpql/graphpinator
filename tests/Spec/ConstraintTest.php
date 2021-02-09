<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use Infinityloop\Utils\Json;

final class ConstraintTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {intMinArg: -20, intMaxArg: 20, intOneOfArg: 1}) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {floatMinArg: 4.01, floatMaxArg: 20.101, floatOneOfArg: 1.01}) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {stringMinArg: "abcd", stringMaxArg: "abcdefghij"}) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {stringRegexArg: "foo", stringOneOfArg: "abc"}) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listMinArg: [1], listMaxArg: [1, 2, 3]}) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listUniqueArg: [1, 2, 3]}) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listInnerListArg: [[1, 2], [1, 3]]}) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listInnerListArg: [[1, 2], null]}) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listMinIntMinArg: [3, 3, 3]}) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {int1: 3}) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldExactlyOne' => 1]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {int2: 3}) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldExactlyOne' => 1]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {int1: null, int2: 3}) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldExactlyOne' => 1]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAOrB { fieldA fieldB } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAOrB' => ['fieldA' => null, 'fieldB' => 1]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAOrB { fieldB } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAOrB' => ['fieldB' => 1]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAOrB { fieldA } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAOrB' => ['fieldA' => null]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName {
                        fieldListConstraint(arg: [
                            { name: "name1", number: [1,2] },
                            { name: "name2", number: [1,3] },
                            { name: "name3", number: [1,5] }
                        ])
                        {
                            fieldName
                        }
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldListConstraint' => [
                            ['fieldName' => 'name1'],
                            ['fieldName' => 'name2'],
                            ['fieldName' => 'name3'],
                        ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param Json $request
     * @param Json $expected
     */
    public function testSimple(Json $request, Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {intMinArg: -21}) }',
                ]),
                \Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {intMaxArg: 21}) }',
                ]),
                \Graphpinator\Exception\Constraint\MaxConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {intOneOfArg: 4}) }',
                ]),
                \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {floatMinArg: 4.0}) }',
                ]),
                \Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {floatMaxArg: 20.1011}) }',
                ]),
                \Graphpinator\Exception\Constraint\MaxConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {floatOneOfArg: 2.03}) }',
                ]),
                \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {stringMinArg: "abc"}) }',
                ]),
                \Graphpinator\Exception\Constraint\MinLengthConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {stringMaxArg: "abcdefghijk"}) }',
                ]),
                \Graphpinator\Exception\Constraint\MaxLengthConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {stringOneOfArg: "abcd"}) }',
                ]),
                \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {stringRegexArg: "fooo"}) }',
                ]),
                \Graphpinator\Exception\Constraint\RegexConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listMinArg: []}) }',
                ]),
                \Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listMaxArg: [1, 2, 3, 4]}) }',
                ]),
                \Graphpinator\Exception\Constraint\MaxItemsConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listUniqueArg: [1, 1]}) }',
                ]),
                \Graphpinator\Exception\Constraint\UniqueConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listMinIntMinArg: [3]}) }',
                ]),
                \Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listMinIntMinArg: [1, 1, 1]}) }',
                ]),
                \Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {}) }',
                ]),
                \Graphpinator\Exception\Constraint\AtLeastOneConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {intMinArg: null}) }',
                ]),
                \Graphpinator\Exception\Constraint\AtLeastOneConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {int1: 3, int2: 3}) }',
                ]),
                \Graphpinator\Exception\Constraint\ExactlyOneConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {}) }',
                ]),
                \Graphpinator\Exception\Constraint\ExactlyOneConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {int1: null}) }',
                ]),
                \Graphpinator\Exception\Constraint\ExactlyOneConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {int2: null}) }',
                ]),
                \Graphpinator\Exception\Constraint\ExactlyOneConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {int1: null, int2: null}) }',
                ]),
                \Graphpinator\Exception\Constraint\ExactlyOneConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName {
                        fieldListConstraint(arg: [
                            { name: "name1", number: [1,2] },
                            { name: "name2", number: [2,2] },
                            { name: "name3", number: [3,3] },
                            { name: "name4", number: [4,5] }
                            { name: "name5", number: [5,5] }
                            { name: "name6", number: [4,4] }
                        ])
                        {
                            fieldName
                        }
                    }',
                ]),
                \Graphpinator\Exception\Constraint\MaxItemsConstraintNotSatisfied::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName {
                        fieldListConstraint(arg: [])
                        {
                            fieldName
                        }
                    }',
                ]),
                \Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param Json $request
     * @param string $exception
     */
    public function testInvalid(Json $request, string $exception) : void
    {
        $this->expectException($exception);
        $this->expectExceptionMessage(\constant($exception . '::MESSAGE'));

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }

    public function testInvalidConstraintTypeString() : void
    {
        $this->expectException(\Graphpinator\Exception\Directive\InvalidConstraintType::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Directive\InvalidConstraintType::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::Float(),
                    )->addDirective(\Graphpinator\Container\Container::directiveStringConstraint(), []),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testInvalidConstraintTypeInt() : void
    {
        $this->expectException(\Graphpinator\Exception\Directive\InvalidConstraintType::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Directive\InvalidConstraintType::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::String(),
                    )->addDirective(\Graphpinator\Container\Container::directiveIntConstraint(), []),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testInvalidConstraintTypeFloat() : void
    {
        $this->expectException(\Graphpinator\Exception\Directive\InvalidConstraintType::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Directive\InvalidConstraintType::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::Int(),
                    )->addDirective(\Graphpinator\Container\Container::directiveFloatConstraint(), []),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testInvalidConstraintTypeList() : void
    {
        $this->expectException(\Graphpinator\Exception\Directive\InvalidConstraintType::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Directive\InvalidConstraintType::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::String(),
                    )->addDirective(\Graphpinator\Container\Container::directiveListConstraint(), []),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testNegativeMinLength() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::String(),
                    )->addDirective(\Graphpinator\Container\Container::directiveStringConstraint(), ['minLength' => -20]),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testNegativeMaxLength() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::String(),
                    )->addDirective(\Graphpinator\Container\Container::directiveStringConstraint(), ['maxLength' => -20]),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testNegativeMinItems() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::String()->list(),
                    )->addDirective(\Graphpinator\Container\Container::directiveListConstraint(), ['minItems' => -20]),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testNegativeMaxItems() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::String()->list()->notNull(),
                    )->addDirective(\Graphpinator\Container\Container::directiveListConstraint(), ['maxItems' => -20]),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testInnerNegativeMinItems() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::String()->list()->notNull(),
                    )->addDirective(
                        \Graphpinator\Container\Container::directiveListConstraint(),
                        ['innerList' => (object) ['minItems' => -20]],
                    ),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testInnerNegativeMaxItems() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::String()->list()->notNull(),
                    )->addDirective(
                        \Graphpinator\Container\Container::directiveListConstraint(),
                        ['innerList' => (object) ['maxItems' => -20]],
                    ),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testEmptyOneOfInt() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::Int(),
                    )->addDirective(\Graphpinator\Container\Container::directiveIntConstraint(), ['oneOf' => []]),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testInvalidOneOfInt() : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::Int(),
                    )->addDirective(\Graphpinator\Container\Container::directiveIntConstraint(), ['oneOf' => ['string']]),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testInvalidOneOfFloat() : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::Float(),
                    )->addDirective(\Graphpinator\Container\Container::directiveFloatConstraint(), ['oneOf' => ['string']]),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testInvalidOneOfString() : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::String(),
                    )->addDirective(\Graphpinator\Container\Container::directiveStringConstraint(), ['oneOf' => [1]]),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testUniqueConstraintList() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\UniqueConstraintOnlyScalar::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\UniqueConstraintOnlyScalar::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        \Graphpinator\Container\Container::String()->notNullList()->list()->notNull(),
                    )->addDirective(\Graphpinator\Container\Container::directiveListConstraint(), ['unique' => true]),
                ]);
            }
        };

        $type->printSchema();
    }

    public function testInvalidAtLeastOneParameter() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::MESSAGE);

        new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(
                    \Graphpinator\Container\Container::directiveObjectConstraint(),
                    ['atLeastOne' => []],
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet();
            }
        };
    }

    public function testInvalidAtLeastOneParameter2() : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(
                    \Graphpinator\Container\Container::directiveObjectConstraint(),
                    ['atLeastOne' => [1]],
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet();
            }
        };
    }

    public function testInvalidExactlyOneParameter() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::MESSAGE);

        new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(
                    \Graphpinator\Container\Container::directiveObjectConstraint(),
                    ['exactlyOne' => []],
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet();
            }
        };
    }

    public function testInvalidConstraintTypeMissingFieldAtLeastOne() : void
    {
        $this->expectException(\Graphpinator\Exception\Directive\InvalidConstraintType::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Directive\InvalidConstraintType::MESSAGE);

        new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(
                    \Graphpinator\Container\Container::directiveObjectConstraint(),
                    ['atLeastOne' => ['arg1', 'arg2']],
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'arg1',
                        \Graphpinator\Container\Container::Int(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'arg3',
                        \Graphpinator\Container\Container::Int(),
                    ),
                ]);
            }
        };
    }

    public function testInvalidConstraintTypeMissingFieldExactlyOne() : void
    {
        $this->expectException(\Graphpinator\Exception\Directive\InvalidConstraintType::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Directive\InvalidConstraintType::MESSAGE);

        new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(
                    \Graphpinator\Container\Container::directiveObjectConstraint(),
                    ['exactlyOne' => ['arg1', 'arg2']],
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'arg1',
                        \Graphpinator\Container\Container::Int(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'arg3',
                        \Graphpinator\Container\Container::Int(),
                    ),
                ]);
            }
        };
    }

    public function fieldConstraintsDataProvider() : array
    {
        return [
            [
                [
                    'type' => \Graphpinator\Container\Container::Int(),
                    'value' => -19,
                    'directive' => \Graphpinator\Container\Container::directiveIntConstraint(),
                    'constraint' => ['min' => -20],
                ],
                Json::fromNative((object) ['data' => ['field1' => -19]]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int(),
                    'value' => 19,
                    'directive' => \Graphpinator\Container\Container::directiveIntConstraint(),
                    'constraint' => ['max' => 20],
                ],
                Json::fromNative((object) ['data' => ['field1' => 19]]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int(),
                    'value' => 2,
                    'directive' => \Graphpinator\Container\Container::directiveIntConstraint(),
                    'constraint' => ['oneOf' => [1, 2, 3]],
                ],
                Json::fromNative((object) ['data' => ['field1' => 2]]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int()->notNullList(),
                    'value' => [1, 2],
                    'directive' => \Graphpinator\Container\Container::directiveIntConstraint(),
                    'constraint' => ['min' => 1],
                ],
                Json::fromNative((object) ['data' => ['field1' => [1, 2]]]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int()->list(),
                    'value' => [1, 2],
                    'directive' => \Graphpinator\Container\Container::directiveIntConstraint(),
                    'constraint' => ['max' => 2],
                ],
                Json::fromNative((object) ['data' => ['field1' => [1, 2]]]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int()->list(),
                    'value' => [1, 2],
                    'directive' => \Graphpinator\Container\Container::directiveListConstraint(),
                    'constraint' => ['unique' => true],
                ],
                Json::fromNative((object) ['data' => ['field1' => [1, 2]]]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int()->list(),
                    'value' => [1, 2],
                    'directive' => \Graphpinator\Container\Container::directiveListConstraint(),
                    'constraint' => ['minItems' => 2, 'maxItems' => 3, 'unique' => true],
                ],
                Json::fromNative((object) ['data' => ['field1' => [1, 2]]]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'value' => [[1, 2]],
                    'directive' => \Graphpinator\Container\Container::directiveListConstraint(),
                    'constraint' => ['innerList' => (object) ['minItems' => 2, 'maxItems' => 3, 'unique' => true]],
                ],
                Json::fromNative((object) ['data' => ['field1' => [[1, 2]]]]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Float(),
                    'value' => 1.00,
                    'directive' => \Graphpinator\Container\Container::directiveFloatConstraint(),
                    'constraint' => ['min' => 0.99],
                ],
                Json::fromNative((object) ['data' => ['field1' => 1.00]]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Float(),
                    'value' => 2.00,
                    'directive' => \Graphpinator\Container\Container::directiveFloatConstraint(),
                    'constraint' => ['max' => 2.01],
                ],
                Json::fromNative((object) ['data' => ['field1' => 2.00]]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Float(),
                    'value' => 2.00,
                    'directive' => \Graphpinator\Container\Container::directiveFloatConstraint(),
                    'constraint' => ['oneOf' => [1.05, 2.00, 2.05]],
                ],
                Json::fromNative((object) ['data' => ['field1' => 2.00]]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::String(),
                    'value' => 'Shrek',
                    'directive' => \Graphpinator\Container\Container::directiveStringConstraint(),
                    'constraint' => ['minLength' => 4],
                ],
                Json::fromNative((object) ['data' => ['field1' => 'Shrek']]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::String(),
                    'value' => 'abc',
                    'directive' => \Graphpinator\Container\Container::directiveStringConstraint(),
                    'constraint' => ['maxLength' => 4],
                ],
                Json::fromNative((object) ['data' => ['field1' => 'abc']]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::String(),
                    'value' => 'beetlejuice',
                    'directive' => \Graphpinator\Container\Container::directiveStringConstraint(),
                    'constraint' => ['regex' => '/^(shrek)|(beetlejuice)$/'],
                ],
                Json::fromNative((object) ['data' => ['field1' => 'beetlejuice']]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::String()->notNullList(),
                    'value' => ['valid', 'valid'],
                    'directive' => \Graphpinator\Container\Container::directiveStringConstraint(),
                    'constraint' => ['maxLength' => 5],
                ],
                Json::fromNative((object) ['data' => ['field1' => ['valid', 'valid']]]),
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Float()->notNullList(),
                    'value' => [1.00, 2.00, 3.00],
                    'directive' => \Graphpinator\Container\Container::directiveFloatConstraint(),
                    'constraint' => ['min' => 1.00, 'max' => 3.00],
                ],
                Json::fromNative((object) ['data' => ['field1' => [1.00, 2.00, 3.00]]]),
            ],
        ];
    }

    /**
     * @dataProvider fieldConstraintsDataProvider
     * @param array $settings
     * @param Json $expected
     */
    public function testFieldConstraints(array $settings, Json $expected) : void
    {
        $request = Json::fromNative((object) [
            'query' => 'query queryName { field1 }',
        ]);

        self::assertSame(
            $expected->toString(),
            self::getGraphpinator($settings)->run(new \Graphpinator\Request\JsonRequestFactory($request))->toString(),
        );
    }

    public function fieldInvalidConstraintsDataProvider() : array
    {
        return [
            [
                [
                    'type' => \Graphpinator\Container\Container::Int(),
                    'value' => -25,
                    'directive' => \Graphpinator\Container\Container::directiveIntConstraint(),
                    'constraint' => ['min' => -20],
                ],
                \Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int(),
                    'value' => 25,
                    'directive' => \Graphpinator\Container\Container::directiveIntConstraint(),
                    'constraint' => ['max' => -20],
                ],
                \Graphpinator\Exception\Constraint\MaxConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int(),
                    'value' => 5,
                    'directive' => \Graphpinator\Container\Container::directiveIntConstraint(),
                    'constraint' => ['oneOf' => [1, 2, 3]],
                ],
                \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int()->list(),
                    'value' => [1, 2],
                    'directive' => \Graphpinator\Container\Container::directiveListConstraint(),
                    'constraint' => ['minItems' => 3],
                ],
                \Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int()->list(),
                    'value' => [1, 2, 3],
                    'directive' => \Graphpinator\Container\Container::directiveListConstraint(),
                    'constraint' => ['maxItems' => 2],
                ],
                \Graphpinator\Exception\Constraint\MaxItemsConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int()->list(),
                    'value' => [1, 2, 2, 3],
                    'directive' => \Graphpinator\Container\Container::directiveListConstraint(),
                    'constraint' => ['unique' => true],
                ],
                \Graphpinator\Exception\Constraint\UniqueConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int()->list(),
                    'value' => [1],
                    'directive' => \Graphpinator\Container\Container::directiveListConstraint(),
                    'constraint' => ['minItems' => 2, 'maxItems' => 3, 'unique' => true],
                ],
                \Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int()->list(),
                    'value' => [1, 2, 3, 4],
                    'directive' => \Graphpinator\Container\Container::directiveListConstraint(),
                    'constraint' => ['minItems' => 2, 'maxItems' => 3, 'unique' => true],
                ],
                \Graphpinator\Exception\Constraint\MaxItemsConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int()->list(),
                    'value' => [1, 2, 2],
                    'directive' => \Graphpinator\Container\Container::directiveListConstraint(),
                    'constraint' => ['minItems' => 2, 'maxItems' => 3, 'unique' => true],
                ],
                \Graphpinator\Exception\Constraint\UniqueConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'value' => [[1]],
                    'directive' => \Graphpinator\Container\Container::directiveListConstraint(),
                    'constraint' => ['innerList' => (object) [
                        'minItems' => 2,
                        'maxItems' => 3,
                    ]],
                ],
                \Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'value' => [[1, 2, 3, 4]],
                    'directive' => \Graphpinator\Container\Container::directiveListConstraint(),
                    'constraint' => ['innerList' => (object) [
                        'minItems' => 2,
                        'maxItems' => 3,
                    ]],
                ],
                \Graphpinator\Exception\Constraint\MaxItemsConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Float(),
                    'value' => 0.10,
                    'directive' => \Graphpinator\Container\Container::directiveFloatConstraint(),
                    'constraint' => ['min' => 0.99],
                ],
                \Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Float(),
                    'value' => 2.01,
                    'directive' => \Graphpinator\Container\Container::directiveFloatConstraint(),
                    'constraint' => ['max' => 2.00],
                ],
                \Graphpinator\Exception\Constraint\MaxConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::Float(),
                    'value' => 5.35,
                    'directive' => \Graphpinator\Container\Container::directiveFloatConstraint(),
                    'constraint' => ['oneOf' => [1.05, 2.00, 2.05]],
                ],
                \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::String(),
                    'value' => 'abc',
                    'directive' => \Graphpinator\Container\Container::directiveStringConstraint(),
                    'constraint' => ['minLength' => 4],
                ],
                \Graphpinator\Exception\Constraint\MinLengthConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::String(),
                    'value' => 'Shrek',
                    'directive' => \Graphpinator\Container\Container::directiveStringConstraint(),
                    'constraint' => ['maxLength' => 4],
                ],
                \Graphpinator\Exception\Constraint\MaxLengthConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::String(),
                    'value' => 'invalid',
                    'directive' => \Graphpinator\Container\Container::directiveStringConstraint(),
                    'constraint' => ['regex' => '/^(shrek)|(beetlejuice)$/'],
                ],
                \Graphpinator\Exception\Constraint\RegexConstraintNotSatisfied::class,
            ],
            [
                [
                    'type' => \Graphpinator\Container\Container::String()->notNullList(),
                    'value' => ['valid', 'invalid'],
                    'directive' => \Graphpinator\Container\Container::directiveStringConstraint(),
                    'constraint' => ['maxLength' => 5],
                ],
                \Graphpinator\Exception\Constraint\MaxLengthConstraintNotSatisfied::class,
            ],
        ];
    }

    /**
     * @dataProvider fieldInvalidConstraintsDataProvider
     * @param array $settings
     * @param string $exception
     */
    public function testFieldInvalidConstraints(array $settings, string $exception) : void
    {
        $this->expectException($exception);
        $this->expectExceptionMessage(\constant($exception . '::MESSAGE'));

        $request = Json::fromNative((object) [
            'query' => 'query queryName { field1 }',
        ]);

        self::getGraphpinator($settings)->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }

    protected static function getGraphpinator(array $settings) : \Graphpinator\Graphpinator
    {
        $query = new class ($settings) extends \Graphpinator\Type\Type
        {
            protected const NAME = 'Query';

            public function __construct(
                private array $settings
            )
            {
                parent::__construct();
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                     \Graphpinator\Field\ResolvableField::create(
                        'field1',
                        $this->settings['type'],
                        function() : mixed {
                            return $this->settings['value'];
                        },
                    )->addDirective($this->settings['directive'], $this->settings['constraint']),
                ]);
            }
        };

        return new \Graphpinator\Graphpinator(
            new \Graphpinator\Type\Schema(
                new \Graphpinator\Container\SimpleContainer([$query], []),
                $query,
            ),
        );
    }
}
