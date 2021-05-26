<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use \Infinityloop\Utils\Json;

final class IntrospectionTest extends \PHPUnit\Framework\TestCase
{
    public function typenameDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ __typename }',
                ]),
                Json::fromNative((object) ['data' => ['__typename' => 'Query']]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldUnion { __typename } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['__typename' => 'Abc']]]),
            ],
        ];
    }

    /**
     * @dataProvider typenameDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testSimple(Json $request, Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function schemaDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ __schema { description } }',
                ]),
                Json::fromNative((object) ['data' => ['__schema' => ['description' => 'Test schema description']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ __schema { queryType {name} } }',
                ]),
                Json::fromNative((object) ['data' => ['__schema' => ['queryType' => ['name' => 'Query']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ __schema { mutationType {name} } }',
                ]),
                Json::fromNative((object) ['data' => ['__schema' => ['mutationType' => null]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ __schema { subscriptionType {name} } }',
                ]),
                Json::fromNative((object) ['data' => ['__schema' => ['subscriptionType' => null]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ __schema { types {name} } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__schema' => [
                            'types' => [
                                ['name' => 'Query'],
                                ['name' => 'Abc'],
                                ['name' => 'Xyz'],
                                ['name' => 'Zzz'],
                                ['name' => 'TestInterface'],
                                ['name' => 'TestUnion'],
                                ['name' => 'TestUnionInvalidResolvedType'],
                                ['name' => 'CompositeInput'],
                                ['name' => 'SimpleInput'],
                                ['name' => 'DefaultsInput'],
                                ['name' => 'SimpleEnum'],
                                ['name' => 'ArrayEnum'],
                                ['name' => 'DescriptionEnum'],
                                ['name' => 'TestScalar'],
                                ['name' => 'UploadType'],
                                ['name' => 'UploadInput'],
                                ['name' => 'Upload'],
                                ['name' => 'ComplexDefaultsInput'],
                                ['name' => 'NullFieldResolution'],
                                ['name' => 'NullListResolution'],
                                ['name' => 'SimpleType'],
                                ['name' => 'InterfaceAbc'],
                                ['name' => 'InterfaceEfg'],
                                ['name' => 'FragmentTypeA'],
                                ['name' => 'FragmentTypeB'],
                                ['name' => 'SimpleEmptyTestInput'],
                                ['name' => 'InterfaceChildType'],
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
                Json::fromNative((object) [
                    'query' => '{ __schema { directives {name description args{name} locations isRepeatable} } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__schema' => [
                            'directives' => [
                                [
                                    'name' => 'testDirective',
                                    'description' => null,
                                    'args' => [],
                                    'locations' => ['FIELD', 'INLINE_FRAGMENT', 'FRAGMENT_SPREAD'],
                                    'isRepeatable' => true,
                                ],
                                [
                                    'name' => 'invalidDirectiveResult',
                                    'description' => null,
                                    'args' => [],
                                    'locations' => ['FIELD', 'INLINE_FRAGMENT', 'FRAGMENT_SPREAD'],
                                    'isRepeatable' => true,
                                ],
                                [
                                    'name' => 'invalidDirectiveType',
                                    'description' => null,
                                    'args' => [],
                                    'locations' => ['FIELD', 'INLINE_FRAGMENT', 'FRAGMENT_SPREAD'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'skip',
                                    'description' => 'Built-in skip directive.',
                                    'args' => [
                                        ['name' => 'if'],
                                    ],
                                    'locations' => ['FIELD', 'INLINE_FRAGMENT', 'FRAGMENT_SPREAD'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'include',
                                    'description' => 'Built-in include directive.',
                                    'args' => [
                                        ['name' => 'if'],
                                    ],
                                    'locations' => ['FIELD', 'INLINE_FRAGMENT', 'FRAGMENT_SPREAD'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'deprecated',
                                    'description' => 'Built-in deprecated directive.',
                                    'args' => [
                                        ['name' => 'reason'],
                                    ],
                                    'locations' => ['FIELD_DEFINITION', 'ENUM_VALUE', 'ARGUMENT_DEFINITION', 'INPUT_FIELD_DEFINITION'],
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
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testSchema(Json $request, Json $expected) : void
    {
        $schema = TestSchema::getSchema();
        $schema->setDescription('Test schema description');
        $graphpinator = new \Graphpinator\Graphpinator($schema);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function typeDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ 
                        __type(name: "Abc") { 
                            kind name description 
                            fields(includeDeprecated: true) {
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
                            specifiedByURL
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'kind' => 'OBJECT',
                            'name' => 'Abc',
                            'description' => 'Test Abc description',
                            'fields' => [['name' => 'fieldXyz']],
                            'interfaces' => [],
                            'possibleTypes' => null,
                            'inputFields' => null,
                            'enumValues' => null,
                            'ofType' => null,
                            'specifiedByURL' => null,
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
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
                            specifiedByURL
                        } 
                    }',
                ]),
                Json::fromNative((object) [
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
                            'specifiedByURL' => null,
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
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
                            specifiedByURL
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'kind' => 'INTERFACE',
                            'name' => 'TestInterface',
                            'description' => 'TestInterface Description',
                            'fields' => [['name' => 'name']],
                            'interfaces' => [],
                            'possibleTypes' => [['name' => 'Xyz']],
                            'inputFields' => null,
                            'enumValues' => null,
                            'ofType' => null,
                            'specifiedByURL' => null,
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ 
                        __type(name: "TestUnion") { 
                            kind name description 
                            fields { name } 
                            interfaces { name }
                            possibleTypes { name }
                            inputFields { name }
                            enumValues { name }
                            ofType { name }
                            specifiedByURL
                        } 
                    }',
                ]),
                Json::fromNative((object) [
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
                            'specifiedByURL' => null,
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ 
                        __type(name: "SimpleInput") { 
                            kind name description 
                            fields { name }
                            interfaces { name }
                            possibleTypes { name }
                            inputFields { name }
                            enumValues { name }
                            ofType { name }
                            specifiedByURL
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'kind' => 'INPUT_OBJECT',
                            'name' => 'SimpleInput',
                            'description' => null,
                            'fields' => null,
                            'interfaces' => null,
                            'possibleTypes' => null,
                            'inputFields' => [['name' => 'name'], ['name' => 'number'], ['name' => 'bool']],
                            'enumValues' => null,
                            'ofType' => null,
                            'specifiedByURL' => null,
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ 
                        __type(name: "SimpleEnum") { 
                            kind name description 
                            fields { name }
                            interfaces { name }
                            possibleTypes { name }
                            inputFields { name }
                            enumValues { name description isDeprecated deprecationReason } 
                            ofType { name }
                            specifiedByURL
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'kind' => 'ENUM',
                            'name' => 'SimpleEnum',
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
                            'specifiedByURL' => null,
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ __type(name: "DescriptionEnum") { 
                        enumValues(includeDeprecated: true){name description isDeprecated deprecationReason} } 
                    }',
                ]),
                Json::fromNative((object) [
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
                                    'description' => 'single line description',
                                    'isDeprecated' => true,
                                    'deprecationReason' => 'reason',
                                ],
                            ],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ __type(name: "DescriptionEnum") { 
                        enumValues(includeDeprecated: false){name description isDeprecated deprecationReason} } 
                    }',
                ]),
                Json::fromNative((object) [
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
                ]),
            ],
            [
                Json::fromNative((object) [
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
                Json::fromNative((object) [
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
                Json::fromNative((object) [
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
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'fields' => [],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
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
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'fields' => [
                                [
                                    'name' => 'fieldXyz',
                                    'description' => null,
                                    'isDeprecated' => true,
                                    'deprecationReason' => null,
                                    'type' => ['name' => 'Xyz'],
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
                                            'type' => ['name' => 'CompositeInput'],
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
                Json::fromNative((object) [
                    'query' => '{ 
                        __type(name: "DefaultsInput") { 
                            inputFields {
                                name description defaultValue
                            } 
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'inputFields' => [
                                [
                                    'name' => 'scalar',
                                    'description' => null,
                                    'defaultValue' => '"defaultString"',
                                ],
                                [
                                    'name' => 'enum',
                                    'description' => null,
                                    'defaultValue' => 'A',
                                ],
                                [
                                    'name' => 'list',
                                    'description' => null,
                                    'defaultValue' => '["string1","string2"]',
                                ],
                                [
                                    'name' => 'object',
                                    'description' => null,
                                    'defaultValue' => '{name:"string",number:[1,2],bool:null}',
                                ],
                                [
                                    'name' => 'listObjects',
                                    'description' => null,
                                    'defaultValue' => '[{name:"string",number:[1],bool:null},{name:"string",number:[],bool:null}]',
                                ],
                            ],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ __type(name: "String") { name kind } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'name' => 'String',
                            'kind' => 'SCALAR',
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ __type(name: "Zzz") { fields {name type{name kind ofType{name}}}}}',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'fields' => [
                                [
                                    'name' => 'enumList',
                                    'type' => [
                                        'name' => null,
                                        'kind' => 'LIST',
                                        'ofType' => [
                                            'name' => 'SimpleEnum',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ __type(name: "TestScalar") { name kind specifiedByURL } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'name' => 'TestScalar',
                            'kind' => 'SCALAR',
                            'specifiedByURL' => null,
                        ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider typeDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testType(Json $request, Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function testDescription() : void
    {
        $request = Json::fromNative((object) [
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
        $expected = Json::fromNative((object) [
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
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function testDeprecatedFields() : void
    {
        $request = Json::fromNative((object) [
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
        $expected = Json::fromNative((object) [
            'data' => [
                '__type' => [
                    'fields' => [],
                ],
            ],
        ]);
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function testDeprecatedFalseEnum() : void
    {
        $request = Json::fromNative((object) [
            'query' => '{ __type(name: "DescriptionEnum") { 
                enumValues(includeDeprecated: false){ name description isDeprecated deprecationReason } } 
            }',
        ]);
        $expected = Json::fromNative((object) [
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
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function testDeprecatedTrueEnum() : void
    {
        $request = Json::fromNative((object) [
            'query' => '{ __type(name: "DescriptionEnum") { 
                enumValues(includeDeprecated: true){name description isDeprecated deprecationReason} } 
            }',
        ]);
        $expected = Json::fromNative((object) [
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
                            'description' => 'single line description',
                            'isDeprecated' => true,
                            'deprecationReason' => 'reason',
                        ],
                    ],
                ],
            ],
        ]);
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }
}
