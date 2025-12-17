<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use Graphpinator\Graphpinator;
use Graphpinator\Normalizer\Exception\UnknownArgument;
use Graphpinator\Parser\Exception\DuplicateArgument;
use Graphpinator\Request\JsonRequestFactory;
use Graphpinator\Value\Exception\InvalidValue;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;

final class ArgumentTest extends TestCase
{
    public static function simpleDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg1: 456) { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 456']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldArgumentDefaults { fieldName fieldNumber fieldBool } }',
                ]),
                Json::fromNative((object) [
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
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldArgumentDefaults(inputBool: false) { fieldName fieldNumber fieldBool } }',
                ]),
                Json::fromNative((object) [
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
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldArgumentDefaults(inputNumberList: [3, 4]) { fieldName fieldNumber fieldBool } }',
                ]),
                Json::fromNative((object) [
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

    public static function invalidDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(argNonExistent: 123) { name } } }',
                ]),
                UnknownArgument::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg1: "123") { name } } }',
                ]),
                InvalidValue::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg1: 123, arg1: 456) { name } } }',
                ]),
                DuplicateArgument::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg1: 2147483649) { name } } }',
                ]),
                InvalidValue::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldArgumentDefaults(inputNumberList: {val: 3}) { fieldName } }',
                ]),
                InvalidValue::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg2: []) { name } } }',
                ]),
                InvalidValue::class,
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
        $graphpinator = new Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
        self::assertNull($result->errors);
    }

    /**
     * @dataProvider invalidDataProvider
     * @param Json $request
     * @param string $exception
     */
    public function testInvalid(Json $request, string $exception) : void
    {
        $this->expectException($exception);

        $graphpinator = new Graphpinator(TestSchema::getSchema());
        $graphpinator->run(new JsonRequestFactory($request));
    }
}
