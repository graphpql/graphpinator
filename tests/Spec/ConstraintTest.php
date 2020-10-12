<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class ConstraintTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {intMinArg: -20, intMaxArg: 20, intOneOfArg: 1}) }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {floatMinArg: 4.01, floatMaxArg: 20.101, floatOneOfArg: 1.01}) }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {stringMinArg: "abcd", stringMaxArg: "abcdefghij"}) }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {stringRegexArg: "foo", stringOneOfArg: "abc"}) }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listMinArg: [1], listMaxArg: [1, 2, 3]}) }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listUniqueArg: [1, 2, 3]}) }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listInnerListArg: [[1, 2], [1, 3]]}) }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listInnerListArg: [[1, 2], null]}) }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listMinIntMinArg: [3, 3, 3]}) }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldConstraint' => 1]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {int1: 3}) }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldExactlyOne' => 1]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {int2: 3}) }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldExactlyOne' => 1]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {int1: null, int2: 3}) }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldExactlyOne' => 1]]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Graphpinator\Json $request
     * @param \Graphpinator\Json $expected
     */
    public function testSimple(\Graphpinator\Json $request, \Graphpinator\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(\Graphpinator\Request::fromJson($request));

        self::assertSame($expected->toString(), $result->toString());
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {intMinArg: -21}) }',
                ]),
                \Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {intMaxArg: 21}) }',
                ]),
                \Graphpinator\Exception\Constraint\MaxConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {intOneOfArg: 4}) }',
                ]),
                \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {floatMinArg: 4.0}) }',
                ]),
                \Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {floatMaxArg: 20.1011}) }',
                ]),
                \Graphpinator\Exception\Constraint\MaxConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {floatOneOfArg: 2.03}) }',
                ]),
                \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {stringMinArg: "abc"}) }',
                ]),
                \Graphpinator\Exception\Constraint\MinLengthConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {stringMaxArg: "abcdefghijk"}) }',
                ]),
                \Graphpinator\Exception\Constraint\MaxLengthConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {stringOneOfArg: "abcd"}) }',
                ]),
                \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {stringRegexArg: "fooo"}) }',
                ]),
                \Graphpinator\Exception\Constraint\RegexConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listMinArg: []}) }',
                ]),
                \Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listMaxArg: [1, 2, 3, 4]}) }',
                ]),
                \Graphpinator\Exception\Constraint\MaxItemsConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listUniqueArg: [1, 1]}) }',
                ]),
                \Graphpinator\Exception\Constraint\UniqueConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listMinIntMinArg: [3]}) }',
                ]),
                \Graphpinator\Exception\Constraint\MinItemsConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {listMinIntMinArg: [1, 1, 1]}) }',
                ]),
                \Graphpinator\Exception\Constraint\MinConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {}) }',
                ]),
                \Graphpinator\Exception\Constraint\AtLeastOneConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldConstraint(arg: {intMinArg: null}) }',
                ]),
                \Graphpinator\Exception\Constraint\AtLeastOneConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {int1: 3, int2: 3}) }',
                ]),
                \Graphpinator\Exception\Constraint\ExactlyOneConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {}) }',
                ]),
                \Graphpinator\Exception\Constraint\ExactlyOneConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {int1: null}) }',
                ]),
                \Graphpinator\Exception\Constraint\ExactlyOneConstraintNotSatisfied::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldExactlyOne(arg: {int1: null, int2: null}) }',
                ]),
                \Graphpinator\Exception\Constraint\ExactlyOneConstraintNotSatisfied::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param \Graphpinator\Json $request
     * @param string $exception
     */
    public function testInvalid(\Graphpinator\Json $request, string $exception) : void
    {
        $this->expectException($exception);
        $this->expectExceptionMessage(\constant($exception . '::MESSAGE'));

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->run(\Graphpinator\Request::fromJson($request));
    }

    public function testInvalidConstraintTypeString() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\InvalidConstraintType::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\InvalidConstraintType::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::Float(),
                    ))->addConstraint(new \Graphpinator\Constraint\StringConstraint()),
                ]);
            }
        };

        $type->getArguments();
    }

    public function testInvalidConstraintTypeInt() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\InvalidConstraintType::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\InvalidConstraintType::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::String(),
                    ))->addConstraint(new \Graphpinator\Constraint\IntConstraint()),
                ]);
            }
        };

        $type->getArguments();
    }

    public function testInvalidConstraintTypeFloat() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\InvalidConstraintType::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\InvalidConstraintType::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::Int(),
                    ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint()),
                ]);
            }
        };

        $type->getArguments();
    }

    public function testNegativeMinLength() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\NegativeLengthParameter::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\NegativeLengthParameter::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::String(),
                    ))->addConstraint(new \Graphpinator\Constraint\StringConstraint(-20)),
                ]);
            }
        };

        $type->getArguments();
    }

    public function testNegativeMaxLength() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\NegativeLengthParameter::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\NegativeLengthParameter::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::String(),
                    ))->addConstraint(new \Graphpinator\Constraint\StringConstraint(null, -20)),
                ]);
            }
        };

        $type->getArguments();
    }

    public function testNegativeMinItems() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\NegativeCountParameter::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\NegativeCountParameter::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::String()->list(),
                    ))->addConstraint(new \Graphpinator\Constraint\ListConstraint(-20)),
                ]);
            }
        };

        $type->getArguments();
    }

    public function testNegativeMaxItems() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\NegativeCountParameter::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\NegativeCountParameter::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::String()->list()->notNull(),
                    ))->addConstraint(new \Graphpinator\Constraint\ListConstraint(null, -20)),
                ]);
            }
        };

        $type->getArguments();
    }

    public function testInnerNegativeMaxItems() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\NegativeCountParameter::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\NegativeCountParameter::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::String()->list()->notNull(),
                    ))->addConstraint(new \Graphpinator\Constraint\ListConstraint(null, null, false, (object) ['minItems' => -20])),
                ]);
            }
        };

        $type->getArguments();
    }

    public function testInvalidOneOfInt() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\InvalidOneOfParameter::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\InvalidOneOfParameter::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::Int(),
                    ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(null, null, ['string'])),
                ]);
            }
        };

        $type->getArguments();
    }

    public function testInvalidOneOfFloat() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\InvalidOneOfParameter::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\InvalidOneOfParameter::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::Float(),
                    ))->addConstraint(new \Graphpinator\Constraint\FloatConstraint(null, null, ['string'])),
                ]);
            }
        };

        $type->getArguments();
    }

    public function testInvalidOneOfString() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\InvalidOneOfParameter::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\InvalidOneOfParameter::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::String(),
                    ))->addConstraint(new \Graphpinator\Constraint\StringConstraint(null, null, null, [1])),
                ]);
            }
        };

        $type->getArguments();
    }

    public function testInvalidConstraintTypeList() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\InvalidConstraintType::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\InvalidConstraintType::MESSAGE);

        $type = new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::String()->notNull(),
                    ))->addConstraint(new \Graphpinator\Constraint\ListConstraint()),
                ]);
            }
        };

        $type->getArguments();
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
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::String()->notNullList()->list()->notNull(),
                    ))->addConstraint(new \Graphpinator\Constraint\ListConstraint(null, null, true)),
                ]);
            }
        };

        $type->getArguments();
    }

    public function testInvalidAtLeastOneParameter() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\InvalidAtLeastOneParameter::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\InvalidAtLeastOneParameter::MESSAGE);

        new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            public function __construct()
            {
                $this->addConstraint(new \Graphpinator\Constraint\ObjectConstraint([]));
            }

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([]);
            }
        };
    }

    public function testInvalidAtLeastOneParameter2() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\InvalidAtLeastOneParameter::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\InvalidAtLeastOneParameter::MESSAGE);

        new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            public function __construct()
            {
                $this->addConstraint(new \Graphpinator\Constraint\ObjectConstraint(['string', 1]));
            }

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([]);
            }
        };
    }

    public function testInvalidExactlyOneParameter() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\InvalidExactlyOneParameter::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\InvalidExactlyOneParameter::MESSAGE);

        new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            public function __construct()
            {
                $this->addConstraint(new \Graphpinator\Constraint\ObjectConstraint(null, ['string', 1]));
            }

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([]);
            }
        };
    }

    public function testInvalidExactlyOneParameter2() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\InvalidExactlyOneParameter::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\InvalidExactlyOneParameter::MESSAGE);

        new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            public function __construct()
            {
                $this->addConstraint(new \Graphpinator\Constraint\ObjectConstraint(null, []));
            }

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([]);
            }
        };
    }

    public function testInvalidConstraintTypeInput() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\InvalidConstraintType::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\InvalidConstraintType::MESSAGE);

        new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            public function __construct()
            {
                $this->addConstraint(new \Graphpinator\Constraint\ObjectConstraint(['arg1', 'arg2']));
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

    public function testInvalidConstraintTypeInput2() : void
    {
        $this->expectException(\Graphpinator\Exception\Constraint\InvalidConstraintType::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Constraint\InvalidConstraintType::MESSAGE);

        new class extends \Graphpinator\Type\InputType {
            protected const NAME = 'ConstraintInput';

            public function __construct()
            {
                $this->addConstraint(new \Graphpinator\Constraint\ObjectConstraint(null, ['arg1', 'arg2']));
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
}
