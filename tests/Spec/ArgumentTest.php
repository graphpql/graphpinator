<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class ArgumentTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg1: 456) { name } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 456']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldArgumentDefaults { fieldName fieldNumber fieldBool } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldArgumentDefaults' => [
                            'fieldName' => 'testValue',
                            'fieldNumber' => [1, 2],
                            'fieldBool' => true,
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldArgumentDefaults(inputBool: false) { fieldName fieldNumber fieldBool } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldArgumentDefaults' => [
                            'fieldName' => 'testValue',
                            'fieldNumber' => [1, 2],
                            'fieldBool' => false,
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldArgumentDefaults(inputNumberList: [3, 4]) { fieldName fieldNumber fieldBool } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldArgumentDefaults' => [
                            'fieldName' => 'testValue',
                            'fieldNumber' => [3, 4],
                            'fieldBool' => true,
                        ],
                    ],
                ]),
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
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(argNonExistent: 123) { name } } }',
                ]),
                \Graphpinator\Exception\Normalizer\UnknownFieldArgument::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg1: "123") { name } } }',
                ]),
                \Graphpinator\Exception\Value\InvalidValue::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg1: 123, arg1: 456) { name } } }',
                ]),
                \Graphpinator\Exception\Parser\DuplicateArgument::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg1: 2147483649) { name } } }',
                ]),
                \Graphpinator\Exception\Value\InvalidValue::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param \Graphpinator\Json $request
     * @param string $exception
     */
    public function testInvalid(\Graphpinator\Json $request, string $exception) : void
    {
        $this->expectException($exception);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }
}
