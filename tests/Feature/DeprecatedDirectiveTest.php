<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Graphpinator;
use Graphpinator\Request\JsonRequestFactory;
use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DeprecatedDirectiveTest extends TestCase
{
    private static ?Type $testType = null;
    private static ?InputType $testInputType = null;
    private static ?Type $query = null;

    public static function createTestType() : Type
    {
        if (self::$testType instanceof Type) {
            return self::$testType;
        }

        self::$testType = new class extends Type {
            protected const NAME = 'TestType';

            public function __construct()
            {
                $this->directiveUsages = new DirectiveUsageSet();

                parent::__construct();
            }

            public function initGetFieldDefinition() : ResolvableFieldSet
            {
                return $this->getFieldDefinition();
            }

            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'testFieldDeprecatedNull',
                        Container::String(),
                        static function () : ?string {
                            return 'test';
                        },
                    )->setDeprecated(
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'testArgumentDeprecatedNull',
                            Container::String(),
                        )->setDeprecated(),
                    ])),
                    ResolvableField::create(
                        'testFieldDeprecatedNotNull',
                        Container::String()->notNull(),
                        static function () : string {
                            return 'test';
                        },
                    )->setDeprecated(
                        'reasonField',
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'testArgumentDeprecatedNotNull',
                            Container::String(),
                        )->setDeprecated('reasonArgument'),
                    ])),
                ]);
            }
        };

        self::$testType->initGetFieldDefinition();

        return self::$testType;
    }

    public static function createTestInputType() : InputType
    {
        if (self::$testInputType instanceof InputType) {
            return self::$testInputType;
        }

        self::$testInputType = new class extends InputType {
            protected const NAME = 'TestInputType';

            public function __construct()
            {
                $this->directiveUsages = new DirectiveUsageSet();

                parent::__construct();
            }

            public function initGetFieldDefinition() : ArgumentSet
            {
                return $this->getFieldDefinition();
            }

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'testDeprecatedNull',
                        Container::String(),
                    )->setDeprecated(),
                    Argument::create(
                        'testDeprecatedNotNull',
                        Container::String(),
                    )->setDeprecated('reasonArgument'),
                ]);
            }
        };

        self::$testInputType->initGetFieldDefinition();

        return self::$testInputType;
    }

    public static function fieldsDataProvider() : array
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

    public static function inputFieldsDataProvider() : array
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

    #[DataProvider('fieldsDataProvider')]
    public function testFieldsDeprecated(Json $request, Json $expected) : void
    {
        $graphpinator = new Graphpinator($this->getSchema(), true);
        $result = $graphpinator->run(new JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    #[DataProvider('inputFieldsDataProvider')]
    public function testInputFieldsDeprecated(Json $request, Json $expected) : void
    {
        $graphpinator = new Graphpinator($this->getSchema(), true);
        $result = $graphpinator->run(new JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    private function getSchema() : Schema
    {
        return new Schema(
            $this->getContainer(),
            $this->getQuery(),
        );
    }

    private function getContainer() : SimpleContainer
    {
        return new SimpleContainer([
            'TestType' => self::createTestType(),
            'TestInputType' => self::createTestInputType(),
            'Query' => $this->getQuery(),
        ], []);
    }

    private function getQuery() : Type
    {
        if (self::$query instanceof Type) {
            return self::$query;
        }

        self::$query = new class extends Type {
            protected const NAME = 'Query';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'field',
                        Container::String(),
                        static function () : ?string {
                            return null;
                        },
                    ),
                ]);
            }
        };

        return self::$query;
    }
}
