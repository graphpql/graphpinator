<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class ErrorsTest extends \PHPUnit\Framework\TestCase
{
    public function tokenizerDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($ var1: Int) { }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        [
                            'message' => \Graphpinator\Exception\Tokenizer\MissingVariableName::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 18]],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { .. fragment }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        [
                            'message' => \Graphpinator\Exception\Tokenizer\InvalidEllipsis::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 19]],
                        ],
                    ],
                ]),
            ],
        ];
    }

    public function parserDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '   ',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        [
                            'message' => \Graphpinator\Exception\Parser\EmptyRequest::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 1]],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'fragment Abc on Abc { field }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        [
                            'message' => \Graphpinator\Exception\Parser\MissingOperation::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 29]],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        [
                            'message' => \Graphpinator\Exception\Parser\UnexpectedEnd::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 30]],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { field } query queryName { field }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        [
                            'message' => \Graphpinator\Exception\Parser\DuplicateOperation::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 27]],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { field } query queryName { field }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        [
                            'message' => \Graphpinator\Exception\Parser\DuplicateOperation::MESSAGE,
                            'locations' => [['line' => 1, 'column' => 27]],
                        ],
                    ],
                ]),
            ],
        ];
    }

    public function normalizerDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) []),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        ['message' => 'Invalid request - "query" key not found in request body JSON.'],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ fieldAbc @blaDirective() { fieldXyz { name } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => 'Unknown directive "blaDirective".']]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ fieldAbc { ... on BlaType { fieldXyz { name } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => 'Unknown type "BlaType".']]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ fieldAbc { ... on String { fieldXyz { name } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        ['message' => 'Fragment type condition must be outputable composite type.'],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ fieldAbc { ... fragmentName } } fragment fragmentName on String { fieldXyz { name } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        ['message' => 'Fragment type condition must be outputable composite type.'],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ fieldAbc { ... on SimpleInput { fieldXyz { name } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        ['message' => 'Fragment type condition must be outputable composite type.'],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ fieldAbc { fieldXyz @testDirective(if: true) { name } } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        ['message' => 'Unknown argument "if" provided for "@testDirective".'],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ fieldInvalidInput { fieldName fieldNumber fieldBool notDefinedField } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        ['message' => 'Unknown field "notDefinedField" requested for type "SimpleType".'],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldFragment { ... interfaceEfgFragment } }
                    fragment interfaceEfgFragment on InterfaceEfg {  
                        ... on InterfaceAbc { name }
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        ['message' => 'Invalid fragment type condition. ("InterfaceAbc" is not instance of "InterfaceEfg").'],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { 
                        fieldAbstractNullList { 
                            ... on Abc { fieldXyz { name } } 
                            ... on Xyz { name }
                        } 
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => 'Server responded with unknown error.']]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ fieldUnion @invalidDirective() { ... on Abc { fieldXyz { name } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => 'Server responded with unknown error.']]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ fieldThrow { fieldXyz { name } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => 'Server responded with unknown error.']]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '{ fieldInvalidType { } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => 'Server responded with unknown error.']]]),
            ],
        ];
    }

    /**
     * @dataProvider tokenizerDataProvider
     * @dataProvider parserDataProvider
     * @dataProvider normalizerDataProvider
     * @param \Graphpinator\Json $request
     * @param \Graphpinator\Json $expected
     */
    public function testSimple(\Graphpinator\Json $request, \Graphpinator\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema(), true);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function psrDataProvider() : array
    {
        $httpRequest = $this->createStub(\Psr\Http\Message\ServerRequestInterface::class);
        $httpRequest->method('getMethod')->willReturn('PUT');

        $httpRequest2 = $this->createStub(\Psr\Http\Message\ServerRequestInterface::class);
        $httpRequest2->method('getParsedBody')->willReturn([]);
        $httpRequest2->method('getHeader')->willReturn(['multipart/form-data; boundary=-------9051914041544843365972754266']);
        $httpRequest2->method('getMethod')->willReturn('POST');

        $httpRequest3 = $this->createStub(\Psr\Http\Message\ServerRequestInterface::class);
        $httpRequest3->method('getParsedBody')->willReturn(['operations' => '{}']);
        $httpRequest3->method('getHeader')->willReturn(['multipart/form-data; boundary=-------9051914041544843365972754266']);
        $httpRequest3->method('getMethod')->willReturn('GET');

        return [
            [
                $httpRequest,
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        ['message' => 'Invalid request - only GET and POST methods are supported.'],
                    ],
                ]),
            ],
            [
                $httpRequest2,
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        ['message' => 'Invalid multipart request - request must be POST and contain "operations" data.'],
                    ],
                ]),
            ],
            [
                $httpRequest3,
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        ['message' => 'Invalid multipart request - request must be POST and contain "operations" data.'],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider psrDataProvider
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Graphpinator\Json $expected
     */
    public function testPsrRequest(\Psr\Http\Message\ServerRequestInterface $request, \Graphpinator\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema(), true);
        $result = $graphpinator->run(new \Graphpinator\Request\PsrRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }
}
