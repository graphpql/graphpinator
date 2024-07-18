<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use Graphpinator\Exception\Value\InvalidValue;
use Graphpinator\Exception\Value\ValueCannotBeNull;
use Graphpinator\Graphpinator;
use Graphpinator\Normalizer\Exception\UnknownVariable;
use Graphpinator\Normalizer\Exception\VariableTypeInputable;
use Graphpinator\Request\Exception\VariablesNotObject;
use Graphpinator\Request\JsonRequestFactory;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;

final class VariableTest extends TestCase
{
    public static function simpleDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int) { fieldAbc { fieldXyz(arg1: $var1) { name } } }',
                    'variables' => (object) ['var1' => 456],
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 456']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int) { fieldAbc { fieldXyz(arg1: $var1) { name } } }',
                    'variables' => (object) ['var1' => 123],
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int = 456) { fieldAbc { fieldXyz(arg1: $var1) { name } } }',
                    'variables' => (object) [],
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 456']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int = 123) { fieldAbc { fieldXyz(arg1: $var1) { name } } }',
                    'variables' => (object) [],
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
        ];
    }

    public static function invalidDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int = "123") { fieldAbc { fieldXyz { name } } }',
                    'variables' => (object) [],
                ]),
                InvalidValue::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int = "123") { fieldAbc { fieldXyz { name } } }',
                    'variables' => (object) [],
                ]),
                InvalidValue::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int = "123") { fieldAbc { fieldXyz { name } } }',
                    'variables' => ['var1' => '123'],
                ]),
                VariablesNotObject::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int!) { fieldAbc { fieldXyz { name } } }',
                    'variables' => (object) [],
                ]),
                ValueCannotBeNull::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Abc) { fieldAbc { fieldXyz { name } } }',
                    'variables' => (object) [],
                ]),
                VariableTypeInputable::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Abc!) { fieldAbc { fieldXyz { name } } }',
                    'variables' => (object) [],
                ]),
                VariableTypeInputable::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg1: $varNonExistent) { name } } }',
                    'variables' => (object) [],
                ]),
                UnknownVariable::class,
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
    }

    /**
     * @dataProvider invalidDataProvider
     * @param Json $request
     */
    public function testInvalid(Json $request, string $exception) : void
    {
        $this->expectException($exception);

        $graphpinator = new Graphpinator(TestSchema::getSchema());
        $graphpinator->run(new JsonRequestFactory($request));
    }
}
