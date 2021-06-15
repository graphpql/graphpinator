<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Infinityloop\Utils\Json;

final class DeprecatedDirectiveTest extends \PHPUnit\Framework\TestCase
{
    private static ?\Graphpinator\Typesystem\Type $testType = null;
    private static ?\Graphpinator\Typesystem\InputType $testInputType = null;

    public static function createTestType() : \Graphpinator\Typesystem\Type
    {
        if (self::$testType instanceof \Graphpinator\Typesystem\Type) {
            return self::$testType;
        }

        self::$testType = new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'TestType';

            public function __construct()
            {
                $this->directiveUsages = new \Graphpinator\DirectiveUsage\DirectiveUsageSet();

                parent::__construct();
            }

            public function initGetFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return $this->getFieldDefinition();
            }

            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'testFieldDeprecatedNull',
                        \Graphpinator\Typesystem\Container::String(),
                        static function () : string {
                            return 'test';
                        },
                    )->setDeprecated()->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'testArgumentDeprecatedNull',
                            \Graphpinator\Typesystem\Container::String(),
                        )->setDeprecated(),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'testFieldDeprecatedNotNull',
                        \Graphpinator\Typesystem\Container::String(),
                        static function () : string {
                            return 'test';
                        },
                    )->setDeprecated('reasonField')->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'testArgumentDeprecatedNotNull',
                            \Graphpinator\Typesystem\Container::String(),
                        )->setDeprecated('reasonArgument'),
                    ])),
                ]);
            }
        };

        self::$testType->initGetFieldDefinition();

        return self::$testType;
    }

    public static function createTestInputType() : \Graphpinator\Typesystem\InputType
    {
        if (self::$testInputType instanceof \Graphpinator\Typesystem\InputType) {
            return self::$testInputType;
        }

        self::$testInputType = new class extends \Graphpinator\Typesystem\InputType {
            protected const NAME = 'TestInputType';

            public function __construct()
            {
                $this->directiveUsages = new \Graphpinator\DirectiveUsage\DirectiveUsageSet();

                parent::__construct();
            }

            public function initGetFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return $this->getFieldDefinition();
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'testDeprecatedNull',
                        \Graphpinator\Typesystem\Container::String(),
                    )->setDeprecated(),
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'testDeprecatedNotNull',
                        \Graphpinator\Typesystem\Container::String(),
                    )->setDeprecated('reasonArgument'),
                ]);
            }
        };

        self::$testInputType->initGetFieldDefinition();

        return self::$testInputType;
    }

    public function fieldsDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{
                        __type(name: "TestType") { 
                            fields(includeDeprecated: true) {
                                isDeprecated
                                deprecationReason
                                args(includeDeprecated: true) {
                                    isDeprecated
                                    deprecationReason
                                }
                            }
                            inputFields(includeDeprecated: true) {
                                isDeprecated
                                deprecationReason
                            }
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'fields' => [
                                [
                                    'isDeprecated' => true,
                                    'deprecationReason' => null,
                                    'args' => [
                                        [
                                            'isDeprecated' => true,
                                            'deprecationReason' => null,
                                        ],
                                    ],
                                ],
                                [
                                    'isDeprecated' => true,
                                    'deprecationReason' => 'reasonField',
                                    'args' => [
                                        [
                                            'isDeprecated' => true,
                                            'deprecationReason' => 'reasonArgument',
                                        ],
                                    ],
                                ],
                            ],
                            'inputFields' => null,
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{
                        __type(name: "TestType") { 
                            fields(includeDeprecated: false) {
                                isDeprecated
                                deprecationReason
                                args(includeDeprecated: false) {
                                    isDeprecated
                                    deprecationReason
                                }
                            }
                            inputFields(includeDeprecated: false) {
                                isDeprecated
                                deprecationReason
                            }
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'fields' => [],
                            'inputFields' => null,
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{
                        __type(name: "TestType") { 
                            fields(includeDeprecated: true) {
                                isDeprecated
                                deprecationReason
                                args(includeDeprecated: false) {
                                    isDeprecated
                                    deprecationReason
                                }
                            }
                            inputFields(includeDeprecated: true) {
                                isDeprecated
                                deprecationReason
                            }
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'fields' => [
                                [
                                    'isDeprecated' => true,
                                    'deprecationReason' => null,
                                    'args' => [],
                                ],
                                [
                                    'isDeprecated' => true,
                                    'deprecationReason' => 'reasonField',
                                    'args' => [],
                                ],
                            ],
                            'inputFields' => null,
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{
                        __type(name: "TestType") { 
                            fields(includeDeprecated: false) {
                                isDeprecated
                                deprecationReason
                                args(includeDeprecated: true) {
                                    isDeprecated
                                    deprecationReason
                                }
                            }
                            inputFields(includeDeprecated: false) {
                                isDeprecated
                                deprecationReason
                            }
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'fields' => [],
                            'inputFields' => null,
                        ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider fieldsDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testFieldsDeprecated(Json $request, Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator($this->getSchema(), true);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function inputFieldsDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{
                        __type(name: "TestInputType") { 
                            inputFields(includeDeprecated: true) {
                                isDeprecated
                                deprecationReason
                            }
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'inputFields' => [
                                [
                                    'isDeprecated' => true,
                                    'deprecationReason' => null,
                                ],
                                [
                                    'isDeprecated' => true,
                                    'deprecationReason' => 'reasonArgument',
                                ],
                            ],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{
                        __type(name: "TestInputType") { 
                            inputFields(includeDeprecated: false) {
                                isDeprecated
                                deprecationReason
                            }
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'inputFields' => [],
                        ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider inputFieldsDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testInputFieldsDeprecated(Json $request, Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator($this->getSchema(), true);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    private function getSchema() : \Graphpinator\Typesystem\Schema
    {
        return new \Graphpinator\Typesystem\Schema(
            $this->getContainer(),
            $this->getQuery(),
        );
    }

    private function getContainer() : \Graphpinator\SimpleContainer
    {
        return new \Graphpinator\SimpleContainer([
            'TestType' => self::createTestType(),
            'TestInputType' => self::createTestInputType(),
        ], []);
    }

    private function getQuery() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'Query';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'field',
                        \Graphpinator\Typesystem\Container::String(),
                        static function () : void {
                        },
                    ),
                ]);
            }
        };
    }
}
