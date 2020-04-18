<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Spec;

final class IntrospectionTest extends \PHPUnit\Framework\TestCase
{
    public function typenameDataProvider() : array
    {
        return [
            [
                '{ __typename }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['__typename' => 'Query']]),
            ],
            [
                '{ field0 { __typename } }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['__typename' => 'Abc']]]),
            ],
        ];
    }

    /**
     * @dataProvider typenameDataProvider
     */
    public function testTypename(string $request, \Infinityloop\Utils\Json $result) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request, \Infinityloop\Utils\Json::fromArray([]))),
        );
    }

    public function schemaDataProvider() : array
    {
        return [
            [
                '{ __schema { description } }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['__schema' => ['description' => 'Test schema description']]]),
            ],
            [
                '{ __schema { queryType {name} } }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['__schema' => ['queryType' => ['name' => 'Query']]]]),
            ],
            [
                '{ __schema { mutationType {name} } }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['__schema' => ['mutationType' => null]]]),
            ],
            [
                '{ __schema { subscriptionType {name} } }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['__schema' => ['subscriptionType' => null]]]),
            ],
            [
                '{ __schema { types {name} } }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['__schema' => ['types' => [
                    ['name' => 'Query'],
                    ['name' => 'Abc'],
                    ['name' => 'Xyz'],
                    ['name' => 'Zzz'],
                    ['name' => 'TestInterface'],
                    ['name' => 'TestUnion'],
                    ['name' => 'TestInput'],
                    ['name' => 'TestInnerInput'],
                    ['name' => 'TestEnum'],
                    ['name' => 'Int'],
                    ['name' => 'Float'],
                    ['name' => 'String'],
                    ['name' => 'Boolean'],
                ]]]]),
            ],
            [
                '{ __schema { directives {name description args{name} locations isRepeatable} } }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['__schema' => ['directives' => [
                    [
                        'name' => 'skip',
                        'description' => 'Built-in skip directive.',
                        'args' => [['name' => 'if']],
                        'locations' => ['FIELD', 'FRAGMENT_SPREAD', 'INLINE_FRAGMENT'],
                        'isRepeatable' => false,
                    ], [
                        'name' => 'include',
                        'description' => 'Built-in include directive.',
                        'args' => [['name' => 'if']],
                        'locations' => ['FIELD', 'FRAGMENT_SPREAD', 'INLINE_FRAGMENT'],
                        'isRepeatable' => false,
                    ], [
                        'name' => 'testDirective',
                        'description' => null,
                        'args' => [],
                        'locations' => ['FIELD'],
                        'isRepeatable' => true,
                    ], [
                        'name' => 'invalidDirective',
                        'description' => null,
                        'args' => [],
                        'locations' => ['FIELD'],
                        'isRepeatable' => true,
                    ],
                ]]]]),
            ],
        ];
    }

    /**
     * @dataProvider schemaDataProvider
     */
    public function testSchema(string $request, \Infinityloop\Utils\Json $result) : void
    {
        $schema = TestSchema::getSchema();
        $schema->setDescription('Test schema description');
        $graphpinator = new \Graphpinator\Graphpinator($schema);

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request, \Infinityloop\Utils\Json::fromArray([]))),
        );
    }

    public function typeDataProvider() : array
    {
        return [
            [
                '{ __type(name: "Abc") { kind name description fields{name} interfaces{name} possibleTypes{name} inputFields{name} enumValues{name} ofType{name} } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'kind' => 'OBJECT',
                        'name' => 'Abc',
                        'description' => 'Test Abc description',
                        'fields' => [['name' => 'field1']],
                        'interfaces' => [],
                        'possibleTypes' => null,
                        'inputFields' => null,
                        'enumValues' => null,
                        'ofType' => null,
                    ]],
                ]),
            ],
            [
                '{ __type(name: "Xyz") { kind name description fields{name} interfaces{name} possibleTypes{name} inputFields{name} enumValues{name} ofType{name} } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'kind' => 'OBJECT',
                        'name' => 'Xyz',
                        'description' => null,
                        'fields' => [['name' => 'name']],
                        'interfaces' => [['name' => 'TestInterface']],
                        'possibleTypes' => null,
                        'inputFields' => null,
                        'enumValues' => null,
                        'ofType' => null,
                    ]],
                ]),
            ],
            [
                '{ __type(name: "TestInterface") { kind name description fields{name} interfaces{name} possibleTypes{name} inputFields{name} enumValues{name} ofType{name} } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'kind' => 'INTERFACE',
                        'name' => 'TestInterface',
                        'description' => null,
                        'fields' => [['name' => 'name']],
                        'interfaces' => [],
                        'possibleTypes' => null,
                        'inputFields' => null,
                        'enumValues' => null,
                        'ofType' => null,
                    ]],
                ]),
            ],
            [
                '{ __type(name: "TestUnion") { kind name description fields{name} interfaces{name} possibleTypes{name} inputFields{name} enumValues{name} ofType{name} } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'kind' => 'UNION',
                        'name' => 'TestUnion',
                        'description' => null,
                        'fields' => null,
                        'interfaces' => null,
                        'possibleTypes' => [['name' => 'Abc'], ['name' => 'Xyz']],
                        'inputFields' => null,
                        'enumValues' => null,
                        'ofType' => null,
                    ]],
                ]),
            ],
            [
                '{ __type(name: "TestInnerInput") { kind name description fields{name} interfaces{name} possibleTypes{name} inputFields{name} enumValues{name} ofType{name} } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'kind' => 'INPUT_OBJECT',
                        'name' => 'TestInnerInput',
                        'description' => null,
                        'fields' => null,
                        'interfaces' => null,
                        'possibleTypes' => null,
                        'inputFields' => [['name' => 'name'], ['name' => 'number'], ['name' => 'bool']],
                        'enumValues' => null,
                        'ofType' => null,
                    ]],
                ]),
            ],
            [
                '{ __type(name: "TestEnum") { kind name description fields{name} interfaces{name} possibleTypes{name} inputFields{name} enumValues{name description isDeprecated deprecationReason} ofType{name} } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
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
                    ]],
                ]),
            ],
            [
                '{ __type(name: "TestInterface") { fields{name description args{name} isDeprecated deprecationReason type {kind name ofType {name}} } } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'fields' => [[
                            'name' => 'name',
                            'description' => null,
                            'args' => [],
                            'isDeprecated' => false,
                            'deprecationReason' => null,
                            'type' => ['kind' => 'NON_NULL', 'name' => null, 'ofType' => ['name' => 'String']],
                        ]],
                    ]],
                ]),
            ],
            [
                '{ __type(name: "Abc") { fields{name description isDeprecated deprecationReason type{name} args{name description type{name} defaultValue} } } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'fields' => [[
                            'name' => 'field1',
                            'description' => null,
                            'isDeprecated' => true,
                            'deprecationReason' => 'Test deprecation reason',
                            'type' => ['name' => 'TestInterface'],
                            'args' => [
                                [
                                    'name' => 'arg1',
                                    'description' => null,
                                    'type' => ['name' => 'Int'],
                                    'defaultValue' => '123',
                                ], [
                                    'name' => 'arg2',
                                    'description' => null,
                                    'type' => ['name' => 'TestInput'],
                                    'defaultValue' => null,
                                ]
                            ],
                        ]],
                    ]],
                ]),
            ],
            [
                '{ __type(name: "String") { name kind } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'name' => 'String',
                        'kind' => 'SCALAR',
                    ]],
                ]),
            ],
            [
                '{ __type(name: "Zzz") { fields {name type{name kind ofType{name}}}}}',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'fields' => [[
                            'name' => 'enumList',
                            'type' => [
                                'name' => null,
                                'kind' => 'LIST',
                                'ofType' => [
                                    'name' => 'TestEnum',
                                ],
                            ],
                       ]],
                    ]],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider typeDataProvider
     */
    public function testType(string $request, \Infinityloop\Utils\Json $result) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request, \Infinityloop\Utils\Json::fromArray([]))),
        );
    }
}
