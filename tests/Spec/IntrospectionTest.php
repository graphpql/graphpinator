<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use Graphpinator\Graphpinator;
use Graphpinator\Request\JsonRequestFactory;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class IntrospectionTest extends TestCase
{
    public static function typenameDataProvider() : array
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

    public static function schemaDataProvider() : array
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
                    'query' => '{ __schema { types {name description} } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__schema' => [
                            'types' => [
                                ['name' => 'Query', 'description' => null],
                                ['name' => 'Abc', 'description' => null],
                                ['name' => 'Xyz', 'description' => null],
                                ['name' => 'Zzz', 'description' => null],
                                ['name' => 'TestInterface', 'description' => null],
                                ['name' => 'TestUnion', 'description' => null],
                                ['name' => 'TestUnionInvalidResolvedType', 'description' => null],
                                ['name' => 'CompositeInput', 'description' => null],
                                ['name' => 'SimpleInput', 'description' => null],
                                ['name' => 'DefaultsInput', 'description' => null],
                                ['name' => 'SimpleEnum', 'description' => null],
                                ['name' => 'ArrayEnum', 'description' => null],
                                ['name' => 'DescriptionEnum', 'description' => null],
                                ['name' => 'TestScalar', 'description' => null],
                                ['name' => 'ComplexDefaultsInput', 'description' => null],
                                ['name' => 'NullFieldResolution', 'description' => null],
                                ['name' => 'NullListResolution', 'description' => null],
                                ['name' => 'SimpleType', 'description' => null],
                                ['name' => 'InterfaceAbc', 'description' => null],
                                ['name' => 'InterfaceEfg', 'description' => null],
                                ['name' => 'FragmentTypeA', 'description' => null],
                                ['name' => 'FragmentTypeB', 'description' => null],
                                ['name' => 'SimpleEmptyTestInput', 'description' => null],
                                ['name' => 'InterfaceChildType', 'description' => null],
                                ['name' => 'ID', 'description' => 'ID built-in type'],
                                ['name' => 'Int', 'description' => 'Int built-in type (32 bit)'],
                                ['name' => 'Float', 'description' => 'Float built-in type'],
                                ['name' => 'String', 'description' => 'String built-in type'],
                                ['name' => 'Boolean', 'description' => 'Boolean built-in type'],
                                ['name' => '__Schema', 'description' => 'Built-in introspection type'],
                                ['name' => '__Type', 'description' => 'Built-in introspection type'],
                                ['name' => '__TypeKind', 'description' => 'Built-in introspection type'],
                                ['name' => '__Field', 'description' => 'Built-in introspection type'],
                                ['name' => '__EnumValue', 'description' => 'Built-in introspection type'],
                                ['name' => '__InputValue', 'description' => 'Built-in introspection type'],
                                ['name' => '__Directive', 'description' => 'Built-in introspection type'],
                                ['name' => '__DirectiveLocation', 'description' => 'Built-in introspection type'],
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
                                    'locations' => ['FIELD'],
                                    'isRepeatable' => true,
                                ],
                                [
                                    'name' => 'invalidDirectiveType',
                                    'description' => null,
                                    'args' => [],
                                    'locations' => ['FIELD'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'skip',
                                    'description' => 'Built-in skip directive',
                                    'args' => [
                                        ['name' => 'if'],
                                    ],
                                    'locations' => ['FIELD', 'INLINE_FRAGMENT', 'FRAGMENT_SPREAD'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'include',
                                    'description' => 'Built-in include directive',
                                    'args' => [
                                        ['name' => 'if'],
                                    ],
                                    'locations' => ['FIELD', 'INLINE_FRAGMENT', 'FRAGMENT_SPREAD'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'deprecated',
                                    'description' => 'Built-in deprecated directive',
                                    'args' => [
                                        ['name' => 'reason'],
                                    ],
                                    'locations' => ['FIELD_DEFINITION', 'ENUM_VALUE', 'ARGUMENT_DEFINITION', 'INPUT_FIELD_DEFINITION'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'specifiedBy',
                                    'description' => 'Built-in specifiedBy directive',
                                    'args' => [
                                        ['name' => 'url'],
                                    ],
                                    'locations' => ['SCALAR'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'oneOf',
                                    'description' => 'Built-in oneOf directive',
                                    'args' => [],
                                    'locations' => ['INPUT_OBJECT'],
                                    'isRepeatable' => false,
                                ],
                            ],
                        ],
                    ],
                ]),
            ],
        ];
    }

    public static function typeDataProvider() : array
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
                            isOneOf
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'kind' => 'OBJECT',
                            'name' => 'Abc',
                            'description' => null,
                            'fields' => [['name' => 'fieldXyz']],
                            'interfaces' => [],
                            'possibleTypes' => null,
                            'inputFields' => null,
                            'enumValues' => null,
                            'ofType' => null,
                            'specifiedByURL' => null,
                            'isOneOf' => null,
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
                            isOneOf
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
                            'isOneOf' => null,
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
                            isOneOf
                        } 
                    }',
                ]),
                Json::fromNative((object) [
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
                            'specifiedByURL' => null,
                            'isOneOf' => null,
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
                            isOneOf
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
                            'isOneOf' => null,
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
                            isOneOf
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
                            'isOneOf' => false,
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
                            isOneOf
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
                            'isOneOf' => null,
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
                                    'defaultValue' => '{name:"string",number:[1,2]}',
                                ],
                                [
                                    'name' => 'listObjects',
                                    'description' => null,
                                    'defaultValue' => '[{name:"string",number:[1]},{name:"string",number:[]}]',
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
                    'query' => '{ __type(name: "TestScalar") { name kind specifiedByURL isOneOf } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'name' => 'TestScalar',
                            'kind' => 'SCALAR',
                            'specifiedByURL' => null,
                            'isOneOf' => null,
                        ],
                    ],
                ]),
            ],
        ];
    }

    #[DataProvider('typenameDataProvider')]
    public function testTypename(Json $request, Json $expected) : void
    {
        $graphpinator = new Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    #[DataProvider('schemaDataProvider')]
    public function testSchema(Json $request, Json $expected) : void
    {
        $schema = TestSchema::getSchema();
        $schema->setDescription('Test schema description');
        $graphpinator = new Graphpinator($schema);
        $result = $graphpinator->run(new JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    #[DataProvider('typeDataProvider')]
    public function testType(Json $request, Json $expected) : void
    {
        $graphpinator = new Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new JsonRequestFactory($request));

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
                    'description' => null,
                    'fields' => [],
                    'interfaces' => [],
                    'possibleTypes' => null,
                    'inputFields' => null,
                    'enumValues' => null,
                    'ofType' => null,
                ],
            ],
        ]);
        $graphpinator = new Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new JsonRequestFactory($request));

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
        $graphpinator = new Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new JsonRequestFactory($request));

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
        $graphpinator = new Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new JsonRequestFactory($request));

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
        $graphpinator = new Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }
}
