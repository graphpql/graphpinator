<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class IntrospectionTest extends \PHPUnit\Framework\TestCase
{
    public function typenameDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ __typename }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['__typename' => 'Query']]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ field0 { __typename } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['__typename' => 'Abc']]]),
            ],
        ];
    }

    /**
     * @dataProvider typenameDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testSimple(\Infinityloop\Utils\Json $request, \Infinityloop\Utils\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->runQuery($request);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertSame(
            $expected['data'],
            \json_decode(\json_encode($result->getData(), \JSON_THROW_ON_ERROR, 512), true, 512, \JSON_THROW_ON_ERROR),
        );
        self::assertNull($result->getErrors());
    }

    public function schemaDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ __schema { description } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['__schema' => ['description' => 'Test schema description']]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ __schema { queryType {name} } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['__schema' => ['queryType' => ['name' => 'Query']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ __schema { mutationType {name} } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['__schema' => ['mutationType' => null]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ __schema { subscriptionType {name} } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['__schema' => ['subscriptionType' => null]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ __schema { types {name} } }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__schema' => [
                            'types' => [
                                ['name' => 'Query'],
                                ['name' => 'Abc'],
                                ['name' => 'Xyz'],
                                ['name' => 'Zzz'],
                                ['name' => 'TestInterface'],
                                ['name' => 'TestUnion'],
                                ['name' => 'TestInput'],
                                ['name' => 'TestInnerInput'],
                                ['name' => 'TestEnum'],
                                ['name' => 'TestExplicitEnum'],
                                ['name' => 'TestScalar'],
                                ['name' => 'ID'],
                                ['name' => 'Int'],
                                ['name' => 'Float'],
                                ['name' => 'String'],
                                ['name' => 'Boolean'],
                                ['name' => '__Schema'],
                                ['name' => '__Type'],
                                ['name' => '__TypeKind'],
                                ['name' => '__Field'],
                                ['name' => '__EnumValue'],
                                ['name' => '__InputValue'],
                                ['name' => '__Directive'],
                                ['name' => '__DirectiveLocation'],
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ __schema { directives {name description args{name} locations isRepeatable} } }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__schema' => [
                            'directives' => [
                                [
                                    'name' => 'testDirective',
                                    'description' => null,
                                    'args' => [],
                                    'locations' => ['FIELD'],
                                    'isRepeatable' => true,
                                ],
                                [
                                    'name' => 'invalidDirective',
                                    'description' => null,
                                    'args' => [],
                                    'locations' => ['FIELD'],
                                    'isRepeatable' => true,
                                ],
                                [
                                    'name' => 'skip',
                                    'description' => 'Built-in skip directive.',
                                    'args' => [
                                        [
                                            'name' => 'if',
                                        ],
                                    ],
                                    'locations' => [
                                        'FIELD',
                                        'FRAGMENT_SPREAD',
                                        'INLINE_FRAGMENT',
                                    ],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'include',
                                    'description' => 'Built-in include directive.',
                                    'args' => [
                                        [
                                            'name' => 'if',
                                        ],
                                    ],
                                    'locations' => [
                                        'FIELD',
                                        'FRAGMENT_SPREAD',
                                        'INLINE_FRAGMENT',
                                    ],
                                    'isRepeatable' => false,
                                ],
                            ],
                        ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider schemaDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $result
     */
    public function testSchema(\Infinityloop\Utils\Json $request, \Infinityloop\Utils\Json $result) : void
    {
        $schema = TestSchema::getSchema();
        $schema->setDescription('Test schema description');
        $graphpinator = new \Graphpinator\Graphpinator($schema);

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request), \JSON_THROW_ON_ERROR, 512),
        );
    }

    public function typeDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ 
                        __type(name: "Abc") { 
                            kind name description fields {
                                name
                            } 
                            interfaces {
                                name
                            } 
                            possibleTypes {
                                name
                            } 
                            inputFields {
                                name
                            } 
                            enumValues {
                                name
                            } 
                            ofType {
                                name
                            } 
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'kind' => 'OBJECT',
                            'name' => 'Abc',
                            'description' => null,
                            'fields' => [['name' => 'field1']],
                            'interfaces' => [],
                            'possibleTypes' => null,
                            'inputFields' => null,
                            'enumValues' => null,
                            'ofType' => null,
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ 
                        __type(name: "Xyz") { 
                            kind name description fields {
                                name
                            } 
                            interfaces {
                                name
                            } 
                            possibleTypes {
                                name
                            } 
                            inputFields {
                                name
                            } 
                            enumValues {
                                name
                            } 
                            ofType {
                                name
                            } 
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'kind' => 'OBJECT',
                            'name' => 'Xyz',
                            'description' => null,
                            'fields' => [['name' => 'name']],
                            'interfaces' => [['name' => 'TestInterface']],
                            'possibleTypes' => null,
                            'inputFields' => null,
                            'enumValues' => null,
                            'ofType' => null,
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ 
                        __type(name: "TestInterface") { 
                            kind name description fields {
                                name
                            } 
                            interfaces {
                                name
                            } 
                            possibleTypes {
                                name
                            } 
                            inputFields {
                                name
                            } 
                            enumValues {
                                name
                            } 
                            ofType {
                                name
                            } 
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'kind' => 'INTERFACE',
                            'name' => 'TestInterface',
                            'description' => null,
                            'fields' => [['name' => 'name']],
                            'interfaces' => [],
                            'possibleTypes' => [['name' => 'Xyz']],
                            'inputFields' => null,
                            'enumValues' => null,
                            'ofType' => null,
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ 
                        __type(name: "TestUnion") { 
                            kind name description 
                            fields { name } 
                            interfaces { name }
                            possibleTypes { name }
                            inputFields { name }
                            enumValues { name }
                            ofType { name }
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'kind' => 'UNION',
                            'name' => 'TestUnion',
                            'description' => null,
                            'fields' => null,
                            'interfaces' => null,
                            'possibleTypes' => [['name' => 'Abc'], ['name' => 'Xyz']],
                            'inputFields' => null,
                            'enumValues' => null,
                            'ofType' => null,
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ 
                        __type(name: "TestInnerInput") { 
                            kind name description 
                            fields { name }
                            interfaces { name }
                            possibleTypes { name }
                            inputFields { name }
                            enumValues { name }
                            ofType { name }
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'kind' => 'INPUT_OBJECT',
                            'name' => 'TestInnerInput',
                            'description' => null,
                            'fields' => null,
                            'interfaces' => null,
                            'possibleTypes' => null,
                            'inputFields' => [['name' => 'name'], ['name' => 'number'], ['name' => 'bool']],
                            'enumValues' => null,
                            'ofType' => null,
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ 
                        __type(name: "TestEnum") { 
                            kind name description 
                            fields { name }
                            interfaces { name }
                            possibleTypes { name }
                            inputFields { name }
                            enumValues { name description isDeprecated deprecationReason } 
                            ofType { name }
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'kind' => 'ENUM',
                            'name' => 'TestEnum',
                            'description' => null,
                            'fields' => null,
                            'interfaces' => null,
                            'possibleTypes' => null,
                            'inputFields' => null,
                            'enumValues' => [
                                ['name' => 'A', 'description' => null, 'isDeprecated' => false, 'deprecationReason' => null],
                                ['name' => 'B', 'description' => null, 'isDeprecated' => false, 'deprecationReason' => null],
                                ['name' => 'C', 'description' => null, 'isDeprecated' => false, 'deprecationReason' => null],
                                ['name' => 'D', 'description' => null, 'isDeprecated' => false, 'deprecationReason' => null],
                            ],
                            'ofType' => null,
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ __type(name: "TestExplicitEnum") { 
                        enumValues(includeDeprecated: true){name description isDeprecated deprecationReason} } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'enumValues' => [
                                ['name' => 'A', 'description' => null, 'isDeprecated' => false, 'deprecationReason' => null],
                                ['name' => 'B', 'description' => null, 'isDeprecated' => false, 'deprecationReason' => null],
                                ['name' => 'C', 'description' => null, 'isDeprecated' => false, 'deprecationReason' => null],
                                ['name' => 'D', 'description' => null, 'isDeprecated' => false, 'deprecationReason' => null],
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ __type(name: "TestExplicitEnum") { 
                        enumValues(includeDeprecated: false){name description isDeprecated deprecationReason} } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'enumValues' => [
                                ['name' => 'A', 'description' => null, 'isDeprecated' => false, 'deprecationReason' => null],
                                ['name' => 'B', 'description' => null, 'isDeprecated' => false, 'deprecationReason' => null],
                                ['name' => 'C', 'description' => null, 'isDeprecated' => false, 'deprecationReason' => null],
                                ['name' => 'D', 'description' => null, 'isDeprecated' => false, 'deprecationReason' => null],
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ 
                        __type(name: "TestInterface") { 
                            fields {
                                name description args { name } 
                                isDeprecated deprecationReason type {
                                    kind name ofType { name }
                                } 
                            } 
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'fields' => [
                                [
                                    'name' => 'name',
                                    'description' => null,
                                    'args' => [],
                                    'isDeprecated' => false,
                                    'deprecationReason' => null,
                                    'type' => ['kind' => 'NON_NULL', 'name' => null, 'ofType' => ['name' => 'String']],
                                ],
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ 
                        __type(name: "Abc") { 
                            fields(includeDeprecated: false) {
                                name description isDeprecated deprecationReason type { name }
                                args {
                                    name description type { name }
                                    defaultValue
                                } 
                            } 
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'fields' => [
                                [
                                    'name' => 'field1',
                                    'description' => null,
                                    'isDeprecated' => false,
                                    'deprecationReason' => null,
                                    'type' => ['name' => 'TestInterface'],
                                    'args' => [
                                        [
                                            'name' => 'arg1',
                                            'description' => null,
                                            'type' => ['name' => 'Int'],
                                            'defaultValue' => '123',
                                        ],
                                        [
                                            'name' => 'arg2',
                                            'description' => null,
                                            'type' => ['name' => 'TestInput'],
                                            'defaultValue' => null,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ 
                        __type(name: "Abc") { 
                            fields(includeDeprecated: true) { 
                                name description isDeprecated deprecationReason type { name }
                                args {
                                    name description type { name }
                                    defaultValue
                                } 
                            } 
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'fields' => [
                                [
                                    'name' => 'field1',
                                    'description' => null,
                                    'isDeprecated' => false,
                                    'deprecationReason' => null,
                                    'type' => ['name' => 'TestInterface'],
                                    'args' => [
                                        [
                                            'name' => 'arg1',
                                            'description' => null,
                                            'type' => ['name' => 'Int'],
                                            'defaultValue' => '123',
                                        ],
                                        [
                                            'name' => 'arg2',
                                            'description' => null,
                                            'type' => ['name' => 'TestInput'],
                                            'defaultValue' => null,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ __type(name: "String") { name kind } }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'name' => 'String',
                            'kind' => 'SCALAR',
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ __type(name: "Zzz") { fields {name type{name kind ofType{name}}}}}',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'fields' => [
                                [
                                    'name' => 'enumList',
                                    'type' => [
                                        'name' => null,
                                        'kind' => 'LIST',
                                        'ofType' => [
                                            'name' => 'TestEnum',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '{ __type(name: "TestScalar") { name kind } }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        '__type' => [
                            'name' => 'TestScalar',
                            'kind' => 'SCALAR',
                        ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider typeDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $result
     */
    public function testType(\Infinityloop\Utils\Json $request, \Infinityloop\Utils\Json $result) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request), \JSON_THROW_ON_ERROR, 512),
        );
    }

    public function testDescription() : void
    {
        $request = \Infinityloop\Utils\Json::fromArray([
            'query' => '{ 
                __type(name: "Abc") { 
                    kind name description fields { name }
                    interfaces { name }
                    possibleTypes { name }
                    inputFields { name }
                    enumValues { name }
                    ofType { name }
                } 
            }',
        ]);
        $graphpinator = new \Graphpinator\Graphpinator(PrintSchema::getSchema());
        $result = \Infinityloop\Utils\Json::fromArray([
            'data' => [
                '__type' => [
                    'kind' => 'OBJECT',
                    'name' => 'Abc',
                    'description' => 'Test Abc description',
                    'fields' => [],
                    'interfaces' => [],
                    'possibleTypes' => null,
                    'inputFields' => null,
                    'enumValues' => null,
                    'ofType' => null,
                ],
            ],
        ]);

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request), \JSON_THROW_ON_ERROR, 512),
        );
    }

    public function testDeprecatedFields() : void
    {
        $request = \Infinityloop\Utils\Json::fromArray([
            'query' => '{ 
                __type(name: "Abc") { 
                    fields(includeDeprecated: false) {
                        name description isDeprecated deprecationReason type { name } 
                        args {
                            name description 
                            type { name } 
                            defaultValue
                        } 
                    } 
                } 
            }',
        ]);
        $graphpinator = new \Graphpinator\Graphpinator(PrintSchema::getSchema());
        $result = \Infinityloop\Utils\Json::fromArray([
            'data' => [
                '__type' => [
                    'fields' => [],
                ],
            ],
        ]);

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request), \JSON_THROW_ON_ERROR, 512),
        );
    }

    public function testDeprecatedFalseEnum() : void
    {
        $request = \Infinityloop\Utils\Json::fromArray([
            'query' => '{ __type(name: "TestExplicitEnum") { 
                enumValues(includeDeprecated: false){ name description isDeprecated deprecationReason } } 
            }',
        ]);
        $graphpinator = new \Graphpinator\Graphpinator(PrintSchema::getSchema());
        $result = \Infinityloop\Utils\Json::fromArray([
            'data' => [
                '__type' => [
                    'enumValues' => [
                        [
                            'name' => 'A',
                            'description' => 'single line description',
                            'isDeprecated' => false,
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'C',
                            'description' => 'multi line' . \PHP_EOL . 'description',
                            'isDeprecated' => false,
                            'deprecationReason' => null,
                        ],
                    ],
                ],
            ],
        ]);

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request), \JSON_THROW_ON_ERROR, 512),
        );
    }

    public function testDeprecatedTrueEnum() : void
    {
        $request = \Infinityloop\Utils\Json::fromArray([
            'query' => '{ __type(name: "TestExplicitEnum") { 
                enumValues(includeDeprecated: true){name description isDeprecated deprecationReason} } 
            }',
        ]);
        $graphpinator = new \Graphpinator\Graphpinator(PrintSchema::getSchema());
        $result = \Infinityloop\Utils\Json::fromArray([
            'data' => [
                '__type' => [
                    'enumValues' => [
                        [
                            'name' => 'A',
                            'description' => 'single line description',
                            'isDeprecated' => false,
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'B',
                            'description' => null,
                            'isDeprecated' => true,
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'C',
                            'description' => 'multi line' . \PHP_EOL . 'description',
                            'isDeprecated' => false,
                            'deprecationReason' => null,
                        ],
                        [
                            'name' => 'D',
                            'description' => 'single line description 2',
                            'isDeprecated' => true,
                            'deprecationReason' => 'reason',
                        ],
                    ],
                ],
            ],
        ]);

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request), \JSON_THROW_ON_ERROR, 512),
        );
    }
}
