<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use Graphpinator\Graphpinator;
use Graphpinator\Parser\Exception\DuplicateOperation;
use Graphpinator\Parser\Exception\EmptyRequest;
use Graphpinator\Parser\Exception\MissingOperation;
use Graphpinator\Parser\Exception\UnexpectedEnd;
use Graphpinator\Request\JsonRequestFactory;
use Graphpinator\Request\PsrRequestFactory;
use Graphpinator\Tokenizer\Exception\InvalidEllipsis;
use Graphpinator\Tokenizer\Exception\MissingVariableName;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

final class ErrorsTest extends TestCase
{
    public static function tokenizerDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($ var1: Int) { }',
                ]),
                Json::fromNative((object) [
                    'errors' => [
                        [
                            'message' => MissingVariableName::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 18]],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { .. fragment }',
                ]),
                Json::fromNative((object) [
                    'errors' => [
                        [
                            'message' => InvalidEllipsis::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 19]],
                        ],
                    ],
                ]),
            ],
        ];
    }

    public static function parserDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '   ',
                ]),
                Json::fromNative((object) [
                    'errors' => [
                        [
                            'message' => EmptyRequest::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 1]],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'fragment Abc on Abc { field }',
                ]),
                Json::fromNative((object) [
                    'errors' => [
                        [
                            'message' => MissingOperation::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 29]],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ',
                ]),
                Json::fromNative((object) [
                    'errors' => [
                        [
                            'message' => UnexpectedEnd::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 30]],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { field } query queryName { field }',
                ]),
                Json::fromNative((object) [
                    'errors' => [
                        [
                            'message' => DuplicateOperation::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 27]],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { field } query queryName { field }',
                ]),
                Json::fromNative((object) [
                    'errors' => [
                        [
                            'message' => DuplicateOperation::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 27]],
                        ],
                    ],
                ]),
            ],
        ];
    }

    public static function normalizerDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) []),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Invalid request - "query" key not found in request body JSON.',
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldAbc @blaDirective() { fieldXyz { name } } }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Unknown directive "blaDirective".',
                        'path' => [' <operation>', 'fieldAbc <field>', 'blaDirective <directive>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldAbc { ... on BlaType { fieldXyz { name } } } }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Unknown type "BlaType".',
                        'path' => [' <operation>', 'fieldAbc <field>', '<inline fragment>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldAbc { ... on String { fieldXyz { name } } } }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Fragment type condition must be outputable composite type.',
                        'path' => [' <operation>', 'fieldAbc <field>', '<inline fragment>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldAbc { ... fragmentName } } fragment fragmentName on String { fieldXyz { name } }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Fragment type condition must be outputable composite type.',
                        'path' => [' <operation>', 'fieldAbc <field>', 'fragmentName <fragment spread>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldAbc { ... on SimpleInput { fieldXyz { name } } } }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Fragment type condition must be outputable composite type.',
                        'path' => [' <operation>', 'fieldAbc <field>', '<inline fragment>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldAbc { fieldXyz @testDirective(if: true) { name } } }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Unknown argument "if" provided.',
                        'path' => [' <operation>', 'fieldAbc <field>', 'fieldXyz <field>', 'testDirective <directive>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldInvalidInput { fieldName fieldNumber fieldBool notDefinedField } }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Unknown field "notDefinedField" requested for type "SimpleType".',
                        'path' => [' <operation>', 'fieldInvalidInput <field>', 'notDefinedField <field>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldFragment { ... fragment } }
                    fragment fragment on InterfaceAbc {  
                        ... on Abc { name }
                    }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Invalid fragment type condition. ("Abc" is not instance of "InterfaceAbc").',
                        'path' => ['queryName <operation>', 'fieldFragment <field>', 'fragment <fragment spread>', '<inline fragment>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int = "123") { fieldAbc { fieldXyz { name } } }',
                    'variables' => (object) [],
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Invalid value resolved for type "Int" - got "123".',
                        'path' => ['queryName <operation>', 'var1 <variable>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int!) { fieldAbc { fieldXyz { name } } }',
                    'variables' => (object) [],
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Not-null type with null value.',
                        'path' => ['queryName <operation>', 'var1 <variable>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { 
                        fieldAbstractNullList { 
                            ... on Abc { fieldXyz { name } } 
                            ... on Xyz { name }
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Server responded with unknown error.',
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldThrow { fieldXyz { __typename } } }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Server responded with unknown error.',
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldInvalidType { __typename } }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Server responded with unknown error.',
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz @skip(if: false) @skip(if: false) { name } } }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Duplicated directive "skip" which is not repeatable.',
                        'path' => ['queryName <operation>', 'fieldAbc <field>', 'fieldXyz <field>', 'skip <directive>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldArgumentDefaults(inputNumberList: [123, "invalid"]) { fieldBool } }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Invalid value resolved for type "Int" - got "invalid".',
                        'path' => ['queryName <operation>', 'fieldArgumentDefaults <field>', 'inputNumberList <argument>', '1 <list index>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldEnumArg(val: 123) }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Invalid value resolved for type "SimpleEnum" - got 123.',
                        'path' => ['queryName <operation>', 'fieldEnumArg <field>', 'val <argument>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: [Int!]!) { fieldArgumentDefaults(inputNumberList: $var1) { fieldBool } }',
                    'variables' => (object) ['var1' => [123, 'invalid']],
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Invalid value resolved for type "Int" - got "invalid".',
                        'path' => ['queryName <operation>', 'var1 <variable>', '1 <list index>'],
                    ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query { fieldUnion { ... on Abc { fieldXyz { ... on InterfaceEfg { __typename name } } } } }',
                ]),
                Json::fromNative((object) [
                    'errors' => [[
                        'message' => 'Invalid fragment type condition. ("InterfaceEfg" is not instance of "Xyz").',
                        'path' => [' <operation>', 'fieldUnion <field>', '<inline fragment>', 'fieldXyz <field>', '<inline fragment>'],
                    ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider tokenizerDataProvider
     * @dataProvider parserDataProvider
     * @dataProvider normalizerDataProvider
     * @param Json $request
     * @param Json $expected
     */
    public function testSimple(Json $request, Json $expected) : void
    {
        $graphpinator = new Graphpinator(TestSchema::getSchema(), true);
        $result = $graphpinator->run(new JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function testPsrRequestPut() : void
    {
        $httpRequest = $this->createStub(ServerRequestInterface::class);
        $httpRequest->method('getMethod')->willReturn('PUT');

        $expected = Json::fromNative((object) [
            'errors' => [
                ['message' => 'Invalid request - only GET and POST methods are supported.'],
            ],
        ]);

        $graphpinator = new Graphpinator(TestSchema::getSchema(), true);
        $result = $graphpinator->run(new PsrRequestFactory($httpRequest));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function testPsrRequestMultipartNoOperations() : void
    {
        $httpRequest = $this->createStub(ServerRequestInterface::class);
        $httpRequest->method('getParsedBody')->willReturn([]);
        $httpRequest->method('getHeader')->willReturn(['multipart/form-data; boundary=-------9051914041544843365972754266']);
        $httpRequest->method('getMethod')->willReturn('POST');

        $expected = Json::fromNative((object) [
            'errors' => [
                ['message' => 'Invalid multipart request - request must be POST and contain "operations" data.'],
            ],
        ]);

        $graphpinator = new Graphpinator(TestSchema::getSchema(), true);
        $result = $graphpinator->run(new PsrRequestFactory($httpRequest));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function testPsrRequestMultipartGet() : void
    {
        $httpRequest = $this->createStub(ServerRequestInterface::class);
        $httpRequest->method('getParsedBody')->willReturn(['operations' => '{}']);
        $httpRequest->method('getHeader')->willReturn(['multipart/form-data; boundary=-------9051914041544843365972754266']);
        $httpRequest->method('getMethod')->willReturn('GET');

        $expected = Json::fromNative((object) [
            'errors' => [
                ['message' => 'Invalid multipart request - request must be POST and contain "operations" data.'],
            ],
        ]);

        $graphpinator = new Graphpinator(TestSchema::getSchema(), true);
        $result = $graphpinator->run(new PsrRequestFactory($httpRequest));

        self::assertSame($expected->toString(), $result->toString());
    }
}
