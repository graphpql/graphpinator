<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use Infinityloop\Utils\Json;

final class SimpleTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { aliasName: fieldAbc { fieldXyz { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['aliasName' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldObjectList { name } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldObjectList' => [['name' => 'testValue1'], ['name' => 'testValue2'], ['name' => 'testValue3']],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { 
                        fieldAbstractList { 
                            ... on Abc { __typename fieldXyz { name } } 
                            ... on Xyz { __typename name }
                        } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAbstractList' => [
                            ['__typename' => 'Abc', 'fieldXyz' => ['name' => 'Test 123']],
                            ['__typename' => 'Abc', 'fieldXyz' => ['name' => 'Test 123']],
                            ['__typename' => 'Xyz', 'name' => 'testName'],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldNull { stringType interfaceType { name } unionType { __typename } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldNull' => null]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldNullList { stringListType interfaceListType { name } unionListType { __typename } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldNullList' => null]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldEmptyObject { fieldNumber } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldEmptyObject' => [
                            'fieldNumber' => null,
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { 
                        fieldMerge(inputComplex: {
                            innerObject: {
                                name: "mergeVal",
                                inner: {
                                    name: "mergeVal2",
                                    number: [8,9]
                                },
                                innerList: [],
                                innerNotNull: {
                                    name: "mergeVal3",
                                    number: [5,5],
                                    bool: true
                                }
                            },
                            innerListObjects: [
                                {
                                    name: "mergeValList1",
                                    inner: {
                                        name: "mergeValList2",
                                        number: [7,9]
                                    },
                                    innerList: [],
                                    innerNotNull: {
                                        name: "mergeValList3",
                                        number: [6,5],
                                        bool: false
                                    }
                                },
                                {
                                    name: "mergeValList4",
                                    inner: {
                                        name: "mergeValList5",
                                        number: [6,9]
                                    },
                                    innerList: [
                                        {
                                            name: "mergeValInnerList1",
                                            number: [1,2]
                                            bool: false
                                        },
                                        {
                                            name: "mergeValInnerList2",
                                            number: [3,2]
                                            bool: true
                                        }
                                    ],
                                    innerNotNull: {
                                        name: "mergeValList6",
                                        number: [7,5],
                                        bool: true
                                    }
                                }
                            ]
                        })
                        {
                            fieldName fieldNumber fieldBool
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldMerge' => [
                            'fieldName' => 'mergeVal mergeVal2 mergeVal3 mergeValList1 mergeValList2 mergeValList4',
                            'fieldNumber' => [5,5,8,9,7,9,6,5,3,2],
                            'fieldBool' => true,
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
    }

    /**
     * @dataProvider simpleDataProvider
     * @param Json $request
     * @param Json $expected
     */
    public function testComponents(Json $request, Json $expected) : void
    {
        $source = new \Graphpinator\Source\StringSource($request['query']);
        $parser = new \Graphpinator\Parser\Parser($source);

        $operationName = $request['operationName']
            ?? null;
        $variables = $request['variables']
            ?? new \stdClass();

        $result = $parser->parse()->normalize(TestSchema::getSchema())->createRequest($operationName, $variables)->execute();

        self::assertSame($expected->toString(), $result->toString());
    }

    /**
     * @dataProvider simpleDataProvider
     * @param Json $request
     * @param Json $expected
     */
    public function testHttpJsonBody(Json $request, Json $expected) : void
    {
        $stream = $this->createStub(\Psr\Http\Message\StreamInterface::class);
        $stream->method('getContents')->willReturn($request->toString());
        $httpRequest = $this->createStub(\Psr\Http\Message\ServerRequestInterface::class);
        $httpRequest->method('getHeader')->willReturn(['application/json']);
        $httpRequest->method('getBody')->willReturn($stream);
        $httpRequest->method('getMethod')->willReturn('GET');

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\PsrRequestFactory($httpRequest));

        self::assertSame($expected->toString(), $result->toString());
    }

    /**
     * @dataProvider simpleDataProvider
     * @param Json $request
     * @param Json $expected
     */
    public function testHttpJsonBodyPost(Json $request, Json $expected) : void
    {
        $stream = $this->createStub(\Psr\Http\Message\StreamInterface::class);
        $stream->method('getContents')->willReturn($request->toString());
        $httpRequest = $this->createStub(\Psr\Http\Message\ServerRequestInterface::class);
        $httpRequest->method('getHeader')->willReturn(['application/json']);
        $httpRequest->method('getBody')->willReturn($stream);
        $httpRequest->method('getMethod')->willReturn('POST');

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\PsrRequestFactory($httpRequest));

        self::assertSame($expected->toString(), $result->toString());
    }

    /**
     * @dataProvider simpleDataProvider
     * @param Json $request
     * @param Json $expected
     */
    public function testHttpGraphQlBody(Json $request, Json $expected) : void
    {
        $stream = $this->createStub(\Psr\Http\Message\StreamInterface::class);
        $stream->method('getContents')->willReturn($request['query']);
        $httpRequest = $this->createStub(\Psr\Http\Message\ServerRequestInterface::class);
        $httpRequest->method('getHeader')->willReturn(['application/graphql']);
        $httpRequest->method('getBody')->willReturn($stream);
        $httpRequest->method('getMethod')->willReturn('GET');

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\PsrRequestFactory($httpRequest));

        self::assertSame($expected->toString(), $result->toString());
    }

    /**
     * @dataProvider simpleDataProvider
     * @param Json $request
     * @param Json $expected
     */
    public function testHttpQueryParams(Json $request, Json $expected) : void
    {
        $params = (array) $request->toNative();

        if (\array_key_exists('variables', $params)) {
            $params['variables'] = Json::fromNative($params['variables'])->toString();
        }

        $httpRequest = $this->createStub(\Psr\Http\Message\ServerRequestInterface::class);
        $httpRequest->method('getHeader')->willReturn([]);
        $httpRequest->method('getQueryParams')->willReturn((array) $request->toNative());
        $httpRequest->method('getMethod')->willReturn('GET');

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\PsrRequestFactory($httpRequest));

        self::assertSame($expected->toString(), $result->toString());
    }

    /**
     * @dataProvider simpleDataProvider
     * @param Json $request
     * @param Json $expected
     */
    public function testHttpMultipartBody(Json $request, Json $expected) : void
    {
        $httpRequest = $this->createStub(\Psr\Http\Message\ServerRequestInterface::class);
        $httpRequest->method('getHeader')->willReturn(['multipart/form-data; boundary=-------9051914041544843365972754266']);
        $httpRequest->method('getMethod')->willReturn('POST');
        $httpRequest->method('getParsedBody')->willReturn(['operations' => $request->toString()]);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\PsrRequestFactory($httpRequest));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz } }',
                ]),
                \Graphpinator\Exception\Resolver\SelectionOnComposite::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz { nonExisting } } }',
                ]),
                \Graphpinator\Exception\Normalizer\UnknownField::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz { name { nonExisting } } } }',
                ]),
                \Graphpinator\Exception\Normalizer\SelectionOnLeaf::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldInvalidType { } }',
                ]),
                \Graphpinator\Exception\Resolver\FieldResultTypeMismatch::class,
            ],
            [
                Json::fromNative((object) []),
                \Graphpinator\Exception\Request\QueryMissing::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 123,
                ]),
                \Graphpinator\Exception\Request\QueryNotString::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => '',
                    'variables' => 'abc',
                ]),
                \Graphpinator\Exception\Request\VariablesNotObject::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => '',
                    'operationName' => 123,
                ]),
                \Graphpinator\Exception\Request\OperationNameNotString::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => '',
                    'operationName' => '',
                    'randomKey' => 'randomVal',
                ]),
                \Graphpinator\Exception\Request\UnknownKey::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldArgumentDefaults(arg: [1,2], arg: false) { fieldName fieldNumber fieldBool } }',
                ]),
                'Exception',
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldRequiredArgumentInvalid { fieldName fieldNumber fieldBool } }',
                ]),
                \Graphpinator\Exception\Value\ValueCannotBeNull::class,
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

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }
}
