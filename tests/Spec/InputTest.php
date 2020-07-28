<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class InputTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1(arg2: null) { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        'field0' => [
                            'field1' => [
                                'name' => 'Test 123',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { 
                        field0 { field1(arg2: {name: "foo", innerList: [], innerNotNull: {name: "bar", number: []} }) { name } } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        'field0' => [
                            'field1' => [
                                'name' => 'Test input: name: foo; inner: ; name: bar; bool: ; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { 
                        field0 { 
                            field1(arg2: {
                                name: "foo", innerList: [], innerNotNull: {
                                    name: "bar", number: [123, 456]
                                } 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        'field0' => [
                            'field1' => [
                                'name' => 'Test input: name: foo; inner: ; name: bar; 0: 123; 1: 456; bool: ; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { 
                        field0 { 
                            field1(arg2: {
                                name: "foo", inner: null, innerList: [], innerNotNull: {
                                    name: "bar", number: []
                                } 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        'field0' => [
                            'field1' => [
                                'name' => 'Test input: name: foo; inner: ; name: bar; bool: ; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { 
                        field0 { 
                            field1(arg2: {
                                name: "foo", innerList: [], innerNotNull: {
                                    name: "bar", number: [], bool: null
                                } 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        'field0' => [
                            'field1' => [
                                'name' => 'Test input: name: foo; inner: ; name: bar; bool: ; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { 
                        field0 { 
                            field1(arg2: {
                                name: "foo", innerList: [], innerNotNull: {
                                    name: "bar", number: [], bool: true
                                } 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        'field0' => [
                            'field1' => [
                                'name' => 'Test input: name: foo; inner: ; name: bar; bool: 1; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { 
                        field0 { 
                            field1(arg2: {
                                name: "foo", innerList: [{
                                    name: "bar", number: []
                                }, 
                                {
                                    name: "bar", number: []
                                }], 
                                innerNotNull: {
                                    name: "bar", number: []
                                } 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        'field0' => [
                            'field1' => [
                                'name' => 'Test input: name: foo; inner: ; name: bar; bool: ; name: bar; bool: ; name: bar; bool: ; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($var1: TestInnerInput = {
                        name: "bar", number: []
                        })
                        { 
                            field0 { 
                                field1(arg2: {
                                    name: "foo", innerList: [$var1, $var1], innerNotNull: $var1 
                                }) 
                                { 
                                    name 
                                } 
                            } 
                        }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        'field0' => [
                            'field1' => [
                                'name' => 'Test input: name: foo; inner: ; name: bar; bool: ; name: bar; bool: ; name: bar; bool: ; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($var1: TestInnerInput) { 
                        field0 { 
                            field1(arg2: {
                                name: "foo", innerList: [$var1, $var1], innerNotNull: $var1 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                    'variables' => ['var1' => ['name' => 'bar', 'number' => []]],
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        'field0' => [
                            'field1' => [
                                'name' => 'Test input: name: foo; inner: ; name: bar; bool: ; name: bar; bool: ; name: bar; bool: ; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($var1: [TestInnerInput] = [
                                {name: "bar", number: []}
                            ]
                        )
                        { 
                            field0 { 
                                field1(arg2: {
                                    name: "foo", innerList: $var1, innerNotNull: {
                                        name: "bar", number: []
                                    } 
                                }) 
                                { 
                                    name 
                                } 
                            } 
                        }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'data' => [
                        'field0' => [
                            'field1' => [
                                'name' => 'Test input: name: foo; inner: ; name: bar; bool: ; name: bar; bool: ; ',
                            ],
                        ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testSimple(\Infinityloop\Utils\Json $request, \Infinityloop\Utils\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->runQuery($request);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertSame($expected['data'], \json_decode(\json_encode($result->getData()), true));
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($var1: Int = "123") { field0 { field1 { name } } }',
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($var1: Int = 123) { field0 { field1 { name } } }',
                    'variables' => ['var1' => '123'],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($var1: Int!) { field0 { field1 { name } } }',
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1(arg1: $varNonExistent) { name } } }',
                ]),
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param \Infinityloop\Utils\Json $request
     */
    public function testInvalid(\Infinityloop\Utils\Json $request) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->runQuery($request);
    }
}
