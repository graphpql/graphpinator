<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class IntrospectionTest extends \PHPUnit\Framework\TestCase
{
    public function typenameDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ __typename }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['__typename' => 'Query']]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ fieldUnion { __typename } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['__typename' => 'Abc']]]),
            ],
        ];
    }

    /**
     * @dataProvider typenameDataProvider
     * @param \Graphpinator\Json $request
     * @param \Graphpinator\Json $expected
     */
    public function testSimple(\Graphpinator\Json $request, \Graphpinator\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function schemaDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ __schema { description } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['__schema' => ['description' => 'Test schema description']]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ __schema { queryType {name} } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['__schema' => ['queryType' => ['name' => 'Query']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ __schema { mutationType {name} } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['__schema' => ['mutationType' => null]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ __schema { subscriptionType {name} } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['__schema' => ['subscriptionType' => null]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ __schema { types {name} } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
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
                                ['name' => 'ConstraintInput'],
                                ['name' => 'ExactlyOneInput'],
                                ['name' => 'ConstraintType'],
                                ['name' => 'SimpleEnum'],
                                ['name' => 'ArrayEnum'],
                                ['name' => 'DescriptionEnum'],
                                ['name' => 'TestScalar'],
                                ['name' => 'AddonType'],
                                ['name' => 'UploadType'],
                                ['name' => 'UploadInput'],
                                ['name' => 'ComplexDefaultsInput'],
                                ['name' => 'DateTime'],
                                ['name' => 'Date'],
                                ['name' => 'EmailAddress'],
                                ['name' => 'Hsla'],
                                ['name' => 'Hsl'],
                                ['name' => 'Ipv4'],
                                ['name' => 'Ipv6'],
                                ['name' => 'Json'],
                                ['name' => 'Mac'],
                                ['name' => 'PhoneNumber'],
                                ['name' => 'PostalCode'],
                                ['name' => 'Rgba'],
                                ['name' => 'Rgb'],
                                ['name' => 'Time'],
                                ['name' => 'Url'],
                                ['name' => 'Void'],
                                ['name' => 'Upload'],
                                ['name' => 'Gps'],
                                ['name' => 'Point'],
                                ['name' => 'NullFieldResolution'],
                                ['name' => 'NullListResolution'],
                                ['name' => 'SimpleType'],
                                ['name' => 'InterfaceAbc'],
                                ['name' => 'InterfaceEfg'],
                                ['name' => 'FragmentTypeA'],
                                ['name' => 'FragmentTypeB'],
                                ['name' => 'ListConstraintInput'],
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
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ __schema { directives {name description args{name} locations isRepeatable} } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
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
                                    'name' => 'intConstraint',
                                    'description' => 'Graphpinator intConstraint directive.',
                                    'args' => [
                                        ['name' => 'min'],
                                        ['name' => 'max'],
                                        ['name' => 'oneOf'],
                                    ],
                                    'locations' => ['ARGUMENT_DEFINITION', 'INPUT_FIELD_DEFINITION', 'FIELD_DEFINITION'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'floatConstraint',
                                    'description' => 'Graphpinator floatConstraint directive.',
                                    'args' => [
                                        ['name' => 'min'],
                                        ['name' => 'max'],
                                        ['name' => 'oneOf'],
                                    ],
                                    'locations' => ['ARGUMENT_DEFINITION', 'INPUT_FIELD_DEFINITION', 'FIELD_DEFINITION'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'stringConstraint',
                                    'description' => 'Graphpinator stringConstraint directive.',
                                    'args' => [
                                        ['name' => 'minLength'],
                                        ['name' => 'maxLength'],
                                        ['name' => 'regex'],
                                        ['name' => 'oneOf'],
                                    ],
                                    'locations' => ['ARGUMENT_DEFINITION', 'INPUT_FIELD_DEFINITION', 'FIELD_DEFINITION'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'listConstraint',
                                    'description' => 'Graphpinator listConstraint directive.',
                                    'args' => [
                                        ['name' => 'minItems'],
                                        ['name' => 'maxItems'],
                                        ['name' => 'unique'],
                                        ['name' => 'innerList'],
                                    ],
                                    'locations' => ['ARGUMENT_DEFINITION', 'INPUT_FIELD_DEFINITION', 'FIELD_DEFINITION'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'objectConstraint',
                                    'description' => 'Graphpinator objectConstraint directive.',
                                    'args' => [
                                        ['name' => 'atLeastOne'],
                                        ['name' => 'exactlyOne'],
                                    ],
                                    'locations' => ['INPUT_OBJECT', 'INTERFACE', 'OBJECT'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'skip',
                                    'description' => 'Built-in skip directive.',
                                    'args' => [
                                        ['name' => 'if'],
                                    ],
                                    'locations' => ['FIELD', 'FRAGMENT_SPREAD', 'INLINE_FRAGMENT'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'include',
                                    'description' => 'Built-in include directive.',
                                    'args' => [
                                        ['name' => 'if'],
                                    ],
                                    'locations' => ['FIELD', 'FRAGMENT_SPREAD', 'INLINE_FRAGMENT'],
                                    'isRepeatable' => false,
                                ],
                                [
                                    'name' => 'deprecated',
                                    'description' => 'Built-in deprecated directive.',
                                    'args' => [
                                        ['name' => 'reason'],
                                    ],
                                    'locations' => ['FIELD_DEFINITION', 'ENUM_VALUE'],
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
     * @param \Graphpinator\Json $request
     * @param \Graphpinator\Json $expected
     */
    public function testSchema(\Graphpinator\Json $request, \Graphpinator\Json $expected) : void
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
                \Graphpinator\Json::fromObject((object) [
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
                        } 
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) [
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
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
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
                \Graphpinator\Json::fromObject((object) [
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
                \Graphpinator\Json::fromObject((object) [
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
                \Graphpinator\Json::fromObject((object) [
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
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
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
                \Graphpinator\Json::fromObject((object) [
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
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ 
                        __type(name: "SimpleInput") { 
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
                \Graphpinator\Json::fromObject((object) [
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
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ 
                        __type(name: "SimpleEnum") { 
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
                \Graphpinator\Json::fromObject((object) [
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
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ __type(name: "DescriptionEnum") { 
                        enumValues(includeDeprecated: true){name description isDeprecated deprecationReason} } 
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) [
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
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ __type(name: "DescriptionEnum") { 
                        enumValues(includeDeprecated: false){name description isDeprecated deprecationReason} } 
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) [
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
                \Graphpinator\Json::fromObject((object) [
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
                \Graphpinator\Json::fromObject((object) [
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
                \Graphpinator\Json::fromObject((object) [
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
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        '__type' => [
                            'fields' => [],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
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
                \Graphpinator\Json::fromObject((object) [
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
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ 
                        __type(name: "DefaultsInput") { 
                            inputFields {
                                name description defaultValue
                            } 
                        } 
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) [
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
                                    'defaultValue' => '"A"',
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
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ __type(name: "String") { name kind } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        '__type' => [
                            'name' => 'String',
                            'kind' => 'SCALAR',
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ __type(name: "Zzz") { fields {name type{name kind ofType{name}}}}}',
                ]),
                \Graphpinator\Json::fromObject((object) [
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
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ __type(name: "TestScalar") { name kind } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
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
     * @param \Graphpinator\Json $request
     * @param \Graphpinator\Json $expected
     */
    public function testType(\Graphpinator\Json $request, \Graphpinator\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function testDescription() : void
    {
        $request = \Graphpinator\Json::fromObject((object) [
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
        $expected = \Graphpinator\Json::fromObject((object) [
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
        $request = \Graphpinator\Json::fromObject((object) [
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
        $expected = \Graphpinator\Json::fromObject((object) [
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
        $request = \Graphpinator\Json::fromObject((object) [
            'query' => '{ __type(name: "DescriptionEnum") { 
                enumValues(includeDeprecated: false){ name description isDeprecated deprecationReason } } 
            }',
        ]);
        $expected = \Graphpinator\Json::fromObject((object) [
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
        $request = \Graphpinator\Json::fromObject((object) [
            'query' => '{ __type(name: "DescriptionEnum") { 
                enumValues(includeDeprecated: true){name description isDeprecated deprecationReason} } 
            }',
        ]);
        $expected = \Graphpinator\Json::fromObject((object) [
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

    public function testConstraintDirectivesSync() : void
    {
        $array = [
            \Graphpinator\Constraint\IntConstraint::class => \Graphpinator\Container\Container::directiveIntConstraint(),
            \Graphpinator\Constraint\FloatConstraint::class => \Graphpinator\Container\Container::directiveFloatConstraint(),
            \Graphpinator\Constraint\StringConstraint::class => \Graphpinator\Container\Container::directiveStringConstraint(),
            \Graphpinator\Constraint\ListConstraint::class => \Graphpinator\Container\Container::directiveListConstraint(),
            \Graphpinator\Constraint\ObjectConstraint::class => \Graphpinator\Container\Container::directiveObjectConstraint(),
        ];

        foreach ($array as $constraintClass => $directive) {
            \assert($directive instanceof \Graphpinator\Directive\Directive);

            $reflection = new \ReflectionClass($constraintClass);
            $constructorArgs = $reflection->getConstructor()->getParameters();

            foreach ($constructorArgs as $arg) {
                self::assertTrue(isset($directive->getArguments()[$arg->getName()]));
            }
        }
    }
}
