<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use \Infinityloop\Utils\Json;

final class DirectiveTest extends \PHPUnit\Framework\TestCase
{
    public static function simpleDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz @skip(if: true) { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => new \stdClass()]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz @skip(if: false) { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz @include(if: true) { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz @include(if: false) { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => new \stdClass()]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz @include(if: false) @skip(if: false) { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => new \stdClass()]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz @include(if: true) @skip(if: true) { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => new \stdClass()]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz @include(if: false) @skip(if: true) { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => new \stdClass()]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz @include(if: true) @skip(if: false) { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { ... @include(if: true) { fieldXyz { name } } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { ... @include(if: false) { fieldXyz { name } } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => new \stdClass()]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { 
                        fieldAbc { ... namedFragment @include(if: true) } } fragment namedFragment on Abc { fieldXyz { name } 
                    }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { 
                        fieldAbc { ... namedFragment @include(if: false) } } fragment namedFragment on Abc { fieldXyz { name } 
                    }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => new \stdClass()]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz @testDirective() { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testSimple(Json $request, Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
        self::assertNull($result->getErrors());
    }

    public function testRepeatable() : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        TestSchema::getSchema()->getContainer()->getDirective('testDirective')::$count = 0;

        self::assertSame(
            Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]])->toString(),
            $graphpinator->run(
                new \Graphpinator\Request\JsonRequestFactory(
                    Json::fromNative((object) [
                        'query' => 'query queryName { fieldAbc { fieldXyz @testDirective @testDirective @testDirective { name } } }',
                    ]),
                ),
            )->toString(),
        );
        self::assertSame(3, TestSchema::getSchema()->getContainer()->getDirective('testDirective')::$count);
    }

    public static function invalidDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz @include(if: false) @include(if: false) { name } } }',
                ]),
                \Graphpinator\Normalizer\Exception\DuplicatedDirective::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz @include(if: false) @testDirective @include(if: false) { name } } }',
                ]),
                \Graphpinator\Normalizer\Exception\DuplicatedDirective::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query @testDirective { fieldAbc { fieldXyz { name } } }',
                ]),
                \Graphpinator\Normalizer\Exception\DirectiveIncorrectLocation::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc @invalidDirectiveType() { fieldXyz { name } } }',
                ]),
                \Graphpinator\Normalizer\Exception\DirectiveIncorrectUsage::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldList @deprecated { name } }',
                ]),
                \Graphpinator\Normalizer\Exception\DirectiveNotExecutable::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param string $exception
     */
    public function testInvalid(Json $request, string $exception) : void
    {
        $this->expectException($exception);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }
}
