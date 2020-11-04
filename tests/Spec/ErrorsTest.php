<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class ErrorsTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
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
                            'locations' => [['line' => 0, 'column' => 18]],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => \Graphpinator\Exception\Parser\UnexpectedEnd::MESSAGE]]]),
            ],
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
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName {
                        fieldListConstraint(arg: [
                            { name: "name1", number: [1,2] },
                            { name: "name2", number: [2,2] },
                            { name: "name3", number: [3,3] },
                            { name: "name4", number: [4,5] }
                            { name: "name5", number: [5,5] }
                            { name: "name6", number: [4,4] }
                        ])
                        {
                            fieldName
                        }
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => 'Max items constraint was not satisfied.']]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName {
                        fieldListConstraint(arg: [])
                        {
                            fieldName
                        }
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => 'Min items constraint was not satisfied.']]]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
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
