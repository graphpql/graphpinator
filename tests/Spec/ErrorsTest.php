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
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion @invalidDirective() { fieldXyz { name } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => 'Server responded with unknown error.']]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldThrow { fieldXyz { name } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => 'Server responded with unknown error.']]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldInvalidInput { fieldName fieldNumber fieldBool notDefinedField } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => 'Server responded with unknown error.']]]),
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
}
